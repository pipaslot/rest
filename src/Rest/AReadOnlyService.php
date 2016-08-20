<?php

namespace Pipas\Rest;

use Pipas\Rest\Result\Contract;
use Pipas\Rest\Result\DataSet;

/**
 * Read only repository for one concrete table
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
abstract class AReadOnlyService extends ABaseService implements IReadOnlyService
{

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
