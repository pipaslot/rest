<?php

namespace Pipas\Rest\Result;

use Pipas\Rest\IReadOnlyService;
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
	 * @param null|string $classType
	 * @return DataArray|DataHash
	 */
	public function mapData($data, $classType = null);

	/**
	 * @param array|null $data
	 * @param int $totalCount
	 * @param $classType
	 * @return DataSet|null
	 */
	public function mapDataSet($data, $totalCount = 0, $classType = DataSet::class);

	/**
	 * @param array|DataArray|DataHash|null $data
	 * @param IReadOnlyService $repository
	 * @param $classType
	 * @return Contract
	 */
	public function mapEntity($data, IReadOnlyService $repository, $classType = Contract::class);

	/**
	 * @param DataHash|null $dataHash
	 * @param IReadOnlyService|null $repository
	 * @param string $classType
	 * @return Contract|null
	 */
	public function convertDataHashToEntity(DataHash $dataHash = null, IReadOnlyService $repository = null, $classType = Contract::class);

	/**
	 * @param DataSet|null $dataSet
	 * @param IReadOnlyService|null $repository
	 * @param string $classType
	 * @return Contract|null
	 */
	public function convertDataSetToEntitySet(DataSet $dataSet = null, IReadOnlyService $repository = null, $classType = Contract::class);

	/**
	 * Parse class and returns names and target classes of annotated properties
	 * @param $className
	 * @return mixed
	 * @throws RestException
	 */
	public function getAnnotatedProperties($className);

}