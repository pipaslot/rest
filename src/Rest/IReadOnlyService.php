<?php

namespace Pipas\Rest;

use Pipas\Rest\Result\DataHash;
use Pipas\Rest\Result\DataSet;

/**
 * Read only repository for one concrete table
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IReadOnlyService
{

	/**
	 * @return IContext
	 */
	public function getContext();

	/**
	 * Find one by ID
	 * @param int $id
	 * @return DataHash|null
	 */
	public function find($id);

	/**
	 * Returns all available records
	 * @return DataSet
	 */
	public function findAll();

	/**
	 * Returns all available records filtered by query
	 * @param array $query
	 * @return DataSet
	 */
	public function findBy(array $query);

	/**
	 * Returns the first from available records filtered by query
	 * @param array $query
	 * @return DataHash|null
	 */
	public function findOneBy(array $query = array());

	/**
	 * Returns target service name
	 * @return string
	 */
	public function getName();
}
