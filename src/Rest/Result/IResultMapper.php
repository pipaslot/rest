<?php

namespace Pipas\Rest\Result;

use Pipas\Rest\RestException;

/**
 * Interface for mapping rest results to objects
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IResultMapper
{
	/**
	 * Maps data array to DataArray and DataHash Objects
	 * @param array $data
	 * @param null|string $hashType
	 * @param null|string $arrayType
	 * @return DataArray|DataHash
	 */
	public function mapData($data, $hashType = DataHash::class, $arrayType = DataArray::class);

	/**
	 * @param array|null $data
	 * @param int $totalCount
	 * @param $classType
	 * @return DataSet|null
	 */
	public function mapDataSet($data, $totalCount = 0, $classType = DataSet::class);

	/**
	 * @param array|DataArray|DataHash|null $data
	 * @param $classType
	 * @return Contract
	 */
	public function mapEntity($data, $classType = Contract::class);

	/**
	 * @param DataHash|null $dataHash
	 * @param string $classType
	 * @return Contract|null
	 */
	public function convertDataHashToEntity(DataHash $dataHash = null, $classType = Contract::class);

	/**
	 * @param DataSet|null $dataSet
	 * @param string $classType
	 * @return Contract|null
	 */
	public function convertDataSetToEntitySet(DataSet $dataSet = null, $classType = Contract::class);

	/**
	 * Parse class and returns names and target classes of annotated properties
	 * @param $className
	 * @return mixed
	 * @throws RestException
	 */
	public function getAnnotatedProperties($className);

}