<?php

namespace Pipas\Rest\Result;

use Nette\Reflection\ClassType;
use Pipas\Rest\RestException;

/**
 * Class ResultMapper mapping array data to objects
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ResultMapper implements IResultMapper
{
	private static $instance;

	/**
	 * @return ResultMapper
	 */
	public static function create()
	{
		if (!self::$instance) {
			self::$instance = new static();
		}
		return self::$instance;
	}

	/** @var array List of properties defined in annotations */
	private $classProperties = array();

	/**
	 * Maps data array to DataArray and DataHash Objects
	 * @param array $data
	 * @param null|string $hashType
	 * @param null|string $arrayType
	 * @return DataArray|DataHash|mixed
	 */
	public function mapData($data, $hashType = DataHash::class, $arrayType = DataArray::class)
	{
		if (!is_array($data)) {
			return $data;
		}
		if (count($data) == 0) {
			return new $hashType();
		}
		if ($hashType == null) {
			$hashType = DataHash::class;
		}
		if ($arrayType == null) {
			$arrayType = DataArray::class;
		}

		return $this->isArrayOfAssociativeArrays($data) ? $this->mapDataArray($data, $arrayType, $hashType) : $this->mapDataHash($data, $hashType);
	}

	/**
	 * @param array|null $data
	 * @param int $totalCount
	 * @param $classType
	 * @return DataSet|null
	 */
	public function mapDataSet($data, $totalCount = 0, $classType = DataSet::class)
	{
		if ($data === null) {
			return null;
		}
		if ($classType == null) {
			$classType = DataSet::class;
		}
		$cData = new \ArrayObject();
		foreach ($data as $key => $row) {
			if (isset($row['id'])) {
				$cData[$row['id']] = $this->mapDataHash($row);
			} else {
				$cData[$key] = ((is_array($row) AND count($row) == 0) OR $this->isArrayOfAssociativeArrays($row)) ? $this->mapDataArray($row) : $this->mapDataHash($row);
			}

		}
		return new $classType($cData, $totalCount);
	}

	/**
	 * @param array|DataArray|DataHash|null $data
	 * @param $classType
	 * @return Contract|DataHash
	 */
	public function mapEntity($data, $classType = Contract::class)
	{
		if ($data === null) {
			return null;
		}
		if ($classType == null) {
			$classType = Contract::class;
		}
		$obj = new $classType();
		$mapped = $this->initDataHash($obj, $data);
		return $mapped;
	}

	/**
	 * @param DataHash|null $dataHash
	 * @param string $classType
	 * @return Contract|null
	 */
	public function convertDataHashToEntity(DataHash $dataHash = null, $classType = Contract::class)
	{
		if ($dataHash == null) {
			return null;
		}
		if ($classType == null) {
			$classType = Contract::class;
		}
		$entity = new $classType();
		foreach ($dataHash->toArray() as $key => $val) {
			$entity->$key = $val;
		}
		return $entity;
	}

	/**
	 * @param DataSet|null $dataSet
	 * @param string $classType
	 * @return DataSet|null
	 */
	public function convertDataSetToEntitySet(DataSet $dataSet = null, $classType = Contract::class)
	{
		if ($dataSet == null) {
			return null;
		}
		if ($classType == null) {
			$classType = Contract::class;
		}
		$set = new DataSet();
		foreach ($dataSet->getData() as $key => $val) {
			$set->offsetSet($key, $this->mapEntity($val, $classType));
		}
		return $set;
	}

	/**
	 * @param array $data
	 * @param string $classType Name of target class extended from DataHash
	 * @return DataHash
	 */
	protected function mapDataHash($data, $classType = DataHash::class)
	{
		$obj = new $classType();
		return $this->initDataHash($obj, $data);
	}

	/**
	 * Apply data to DataHash
	 * @param DataHash $hash
	 * @param array $data
	 * @return DataHash
	 */
	protected function initDataHash(DataHash $hash, $data)
	{
		if ($data != null) {
			$defaultObjects = $this->getAnnotatedProperties(get_class($hash));
			foreach ($data as $key => $value) {
				if (isset($defaultObjects[$key])) {
					$hash->initializeProperty($key, $this->mapDataHash($value));
				} else if (is_array($value)) {
					$hash->initializeProperty($key, $this->isAssociativeArray($value) ? $this->mapDataHash($value) : $this->mapDataArray($value));
				} else {
					$hash->initializeProperty($key, $this->mapData($value));
				}
			}
		}
		return $hash;
	}

	/**
	 * @param array $data
	 * @param string $arrayType
	 * @param $hashType
	 * @return DataArray
	 */
	protected function mapDataArray($data, $arrayType = DataArray::class, $hashType = DataHash::class)
	{
		/** @var DataArray $obj */
		$obj = new $arrayType();
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$obj->offsetSet($key, $this->isArrayOfAssociativeArrays($value) ? $this->mapDataArray($value, $arrayType, $hashType) : $this->mapDataHash($value, $hashType));
			} else {
				$obj->offsetSet($key, $value);
			}
		}
		return $obj;
	}

	/*************************** Annotation parsing *****************************/
	/**
	 * Parse class and returns names and target classes of annotated properties
	 * @param $className
	 * @return mixed
	 * @throws RestException
	 */
	public function getAnnotatedProperties($className)
	{
		if (!isset($this->classProperties[$className])) {
			$this->classProperties[$className] = array();
			$ref = new ClassType($className);
			if ($ref->isAbstract() OR $ref->isInterface()) {
				throw new RestException("Class can not be either abstract nor interface");
			}
			$ann = $ref->getAnnotations();
			$parents = class_parents($className);
			$parents[$className] = $className;
			if ($className != DataHash::class AND (!$parents OR !in_array(DataHash::class, $parents))) {
				throw RestException::notInheritedForm($className, DataHash::class);
			}
			$this->parseProperties($ref, $ann, 'property');
			$this->parseProperties($ref, $ann, 'property-read');
		}
		return $this->classProperties[$className];
	}

	/**
	 * Parse Annotation
	 * @param ClassType $ref
	 * @param array $annotations
	 * @param string $type name of annotation
	 * @throws RestException
	 */
	private function parseProperties(ClassType $ref, array $annotations, $type)
	{
		if (!isset($annotations[$type])) {
			return;
		}
		foreach ($annotations[$type] as $val) {
			$trimmed = trim(preg_replace('!\s+!', ' ', $val));//Replace multiple whitespaces
			$expectedClassName = $className = strstr($trimmed, ' ', true);
			//Try find full name of existing class
			if (!class_exists($className)) {
				$expectedClassName = $ref->getNamespaceName() . '\\' . trim($className, '\\');

				if (!class_exists($expectedClassName)) {
					$expectedClassName = $this->getClassNameFromAlias($ref, $className);
					if (!$expectedClassName OR !class_exists($expectedClassName)) {
						continue;
					}
				}
			}
			$parents = class_parents($expectedClassName);
			if ($expectedClassName != DataHash::class AND (!$parents OR !in_array(DataHash::class, $parents))) {
				throw RestException::notInheritedForm($expectedClassName, DataHash::class);
			}

			$prop = strstr($trimmed, '$');
			$pos = strpos($prop, ' ');
			$propertyName = $pos != false ? substr($prop, 1, $pos - 1) : substr($prop, 1);

			$property = $this->getClassPropertyByName($ref, $propertyName);
			if ($property AND !$property->protected) {
				throw RestException::notProtectedProperty($ref->getName(), $propertyName);
			}
			$this->classProperties[$ref->getName()][$propertyName] = $expectedClassName;
		}
	}

	private function getClassNameFromAlias(ClassType $ref, $aliasSourceName)
	{
		$fileContent = file_get_contents($ref->fileName);

		preg_match_all('/use ([a-zA-Z0-9_\\\\]+) as ([a-zA-Z0-9_\\\\]+);/', $fileContent, $matches);
		if (is_array($matches) AND count($matches) > 0) {
			for ($i = 0; $i < count($matches[0]); $i++) {
				if ($matches[2][$i] == $aliasSourceName) {
					return $matches[1][$i];
				}
			}
		}
		return null;
	}

	/**
	 * @param ClassType $ref
	 * @param $name
	 * @return \Nette\Reflection\Property|null
	 */
	private function getClassPropertyByName(ClassType $ref, $name)
	{
		foreach ($ref->properties as $prop) {
			if ($prop->name == $name) {
				return $prop;
			}
		}
		return null;
	}
	/*************************** Helpers *****************************/
	/**
	 * Return if array is associative
	 * @param array $arr
	 * @return bool
	 */
	private function isAssociativeArray(array $arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
	 * Returns if array is of associative arrays
	 * @param array $arr
	 * @return bool
	 */
	private function isArrayOfAssociativeArrays(array $arr)
	{
		if ($this->isAssociativeArray($arr)) {
			return false;
		}
		foreach ($arr as $sub) {
			if ($sub == null) {
				continue;
			}
			if (!is_array($sub) OR !$this->isAssociativeArray($sub)) {
				return false;
			}
		}
		return true;
	}
}