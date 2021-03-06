<?php

namespace Pipas\Rest\Result;

/**
 * Description of Contract
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class Contract extends DataHash implements IContract
{
	/** @var int */
	public $id;

	/**
	 *
	 * Creates a specific object from the more abstract DataHash object if it passes a null, not end the program error but also returns a null value
	 * @param DataHash $dataHash
	 * @return Contract|null
	 */
	public static function fromDataHash(DataHash $dataHash = null)
	{
		if ($dataHash == null) {
			return null;
		}
		$entity = new static();
		foreach ($dataHash->toArray() as $key => $val) {
			$entity->$key = $val;
		}
		return $entity;
	}

	public function __sleep()
	{
		return array();
	}

	function __toString()
	{
		return get_class($this) . ':' . $this->id;
	}

	/**
	 * @param $id
	 * @return self
	 */
	function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return int|null
	 */
	function getId()
	{
		return $this->id;
	}
}
