<?php

namespace Pipas\Rest\Result;

use Pipas\Rest\IReadOnlyService;

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
     * @var IReadOnlyService
     */
    private $_service;

    /**
     *
     * @param IReadOnlyService $repository
     */
    public function __construct(IReadOnlyService $repository = null)
    {
        $this->_service = $repository;
    }

    /**
     * Vytvoří konkrétní objekt z abstraktnějšího DataHash objektu, pokud se předá jako null, nezkončí program chybu ale vrátí také null hodnotu
	 * @param \Pipas\Rest\Result\DataHash $dataHash
     * @param IReadOnlyService $repository
     * @return \static
     */
    public static function fromDataHash(DataHash $dataHash = null, IReadOnlyService $repository = null)
    {
        if ($dataHash == null) return null;
        $entity = new static($repository);
        foreach ($dataHash->toArray() as $key => $val) {
            $entity->$key = $val;
        }
        return $entity;
    }

    /**
     * @return IReadOnlyService
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     *
     * @param IReadOnlyService $service
     * @return self
     */
    public function setService(IReadOnlyService $service = null)
    {
        $this->_service = $service;
        return $this;
    }

    public function __sleep()
    {
        $this->_service = null;
        return array();
    }

    protected function toArrayFilter($propertyName, $value)
    {
        if (parent::toArrayFilter($propertyName, $value) OR $propertyName === "_repository") return true;
        return false;
    }

	function __toString()
	{
		return get_class($this) . ':' . $this->id;
	}


	/**
	 * Convert entity to array for create operation
	 * @return array
	 */
	function toArrayForCreate()
	{
		return $this->toArray(false, true);
	}

	/**
	 * Convert entity to array for update operation
	 * @return array
	 */
	function toArrayForUpdate()
	{
		return $this->toArray(false, true);
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
