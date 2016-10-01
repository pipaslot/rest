<?php

namespace Pipas\Rest;

use Pipas\Rest\Result\IContract;

/**
 * Default abstract read-write repository
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
abstract class AService extends AServiceReadOnly implements IService
{

	/**
	 * Create new record by entity
	 * @param IContract $entity
	 * @return IContract
	 */
	public function create(IContract $entity)
	{
		$arr = $this->prepareDataForCreate($entity->toArray(false, true), $entity);
		if (isset($arr['id'])) {
			unset($arr['id']);
		}
		$entity->setId($this->driver->create($this->getName(), $arr));
		return $entity;
	}

	/**
	 * @param array $data
	 * @param IContract $entity
	 * @return array
	 */
	protected function prepareDataForCreate(array $data, IContract $entity)
	{
		return $data;
	}

	/**
	 * Update existing record
	 * @param IContract $entity
	 * @return bool if update was successfully
	 * @throws RestException
	 */
	public function update(IContract $entity)
	{
		if (!$entity->getId()) {
			throw new RestException("Missing entity ID");
		}
		$arr = $this->prepareDataForUpdate($entity->toArray(false, true), $entity);
		if (isset($arr['id'])) {
			unset($arr['id']);
		}
		return $this->driver->update($this->getName() . '/' . $entity->getId(), $arr);
	}

	/**
	 * @param array $data
	 * @param IContract $entity
	 * @return array
	 */
	protected function prepareDataForUpdate(array $data, IContract $entity)
	{
		return $data;
	}

	/**
	 * Delete record by entity
	 * @param IContract $entity
	 * @return bool if delete was successfully
	 * @throws \Pipas\Rest\RestException
	 */
	public function delete(IContract $entity)
	{
		if (!$entity->getId()) {
			throw new RestException("Missing entity ID");
		}
		return $this->driver->delete($this->getName() . '/' . $entity->getId());
	}

}
