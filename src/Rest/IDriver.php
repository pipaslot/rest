<?php

namespace Pipas\Rest;

use Nette\Http\Url;
use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\DataSet;

/**
 * Realise commands though connection object
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IDriver
{
	/**
	 * REST connection
	 * @return IConnection
	 */
	function getConnection();

	/**
	 * Find one by ID
	 * @param string $serviceName
	 * @param int $id
	 * @param string $mapToType Target object type used for mapping result data
	 * @return null|DataHash|Contract
	 */
	function find($serviceName, $id, $mapToType = DataHash::class);

	/**
	 * Returns all available records
	 * @param string $serviceName
	 * @param string $mapToType Target object type used for mapping result data items to DataSet
	 * @return DataSet|DataHash[]|Contract[]
	 */
	function findAll($serviceName, $mapToType = DataHash::class);

	/**
	 * Returns all available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @param string $mapToType Target object type used for mapping result data items to DataSet
	 * @return DataSet|DataHash[]|Contract[]
	 */
	function findBy($serviceName, array $query, $mapToType = DataHash::class);

	/**
	 * Returns the first from available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @param string $mapToType Target object type used for mapping result data
	 * @return DataHash|Contract|null
	 */
	function findOneBy($serviceName, array $query = array(), $mapToType = DataHash::class);

	/**
	 * Create new record
	 * @param string $serviceName
	 * @param array $entity
	 * @return int New ID
	 */
	function create($serviceName, array $entity);

	/**
	 * Update record
	 * @param string $serviceName
	 * @param array $entity
	 * @return bool
	 */
	function update($serviceName, array $entity);

	/**
	 * Delete record
	 * @param string $serviceName full name with Id
	 * @param array $query
	 * @return bool
	 */
	function delete($serviceName, array $query = array());

	/**
	 * Builds URL for connection
	 * @param $serviceName
	 * @param array $query
	 * @return Url
	 */
	function buildUrl($serviceName, $query = array());

}