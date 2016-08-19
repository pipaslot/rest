<?php

namespace Pipas\Rest;

use Nette\Utils\Strings;
use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataSet;
use Pipas\Rest\Result\IResultMapper;
use Pipas\Rest\Result\ResultMapper;

/**
 * Read only repository for one concrete table
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
abstract class AReadOnlyService implements IReadOnlyService
{
	/** @var ResultMapper */
	protected $resultMapper;
	/** @var IDriver */
	protected $driver;

	function __construct(IDriver $driver, IResultMapper $resultMapper = null)
	{
		$this->driver = $driver;
		$this->resultMapper = $resultMapper == null ? ResultMapper::create() : $resultMapper;
	}

	/**
	 * Returns target service name<br>
	 * As default it convert name of class 'MyExtraService' to 'myExtra'
	 * @return string
	 */
	public function getName()
	{
		$class = get_called_class();
		$slashPos = strrpos($class, "\\");
		$className = trim(substr($class, $slashPos), "\\");
		if (Strings::endsWith($className, self::NAME_SUFFIX)) {
			$className = substr($className, 0, strlen($className) - strlen(self::NAME_SUFFIX));
		}
		return lcfirst($className);
	}

	/**
	 * Name of Contract what is used in Service
	 * @return mixed
	 */
	protected abstract function getContractName();

	/**
	 * Find one by ID
	 * @param int $id
	 * @return Contract
	 */
	public function find($id)
	{
		return $this->resultMapper->mapEntity($this->driver->find($this->getName(), $id), $this->getContractName());
	}

	/**
	 * Returns all available records
	 * @return DataSet
	 */
	public function findAll()
	{
		return $this->resultMapper->convertDataSetToEntitySet($this->driver->findAll($this->getName()), $this->getContractName());
	}

	/**
	 * Returns all available records filtered by query
	 * @param array $query
	 * @return DataSet
	 */
	public function findBy(array $query)
	{
		return $this->resultMapper->convertDataSetToEntitySet($this->driver->findBy($this->getName(), $query), $this->getContractName());
	}

	/**
	 * Returns the first from available records filtered by query
	 * @param array $query
	 * @return Contract
	 */
	public function findOneBy(array $query = array())
	{
		return $this->resultMapper->mapEntity($this->driver->findOneBy($this->getName(), $query), $this->getContractName());
	}

}
