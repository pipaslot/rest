<?php

namespace Pipas\Rest;

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
	 * @param $id
	 * @return DataHash|null
	 */
	function find($serviceName, $id);

	/**
	 * Returns all available records
	 * @param string $serviceName
	 * @return DataSet
	 */
	function findAll($serviceName);

	/**
	 * Returns all available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @return DataSet
	 */
	function findBy($serviceName, array $query);

	/**
	 * Returns the first from available records filtered by query
	 * @param string $serviceName
	 * @param array $query
	 * @return DataHash|null
	 */
	function findOneBy($serviceName, array $query = array());

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

}