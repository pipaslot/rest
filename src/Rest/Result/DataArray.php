<?php

namespace Pipas\Rest\Result;

/**
 * Array used for iterations under objects
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class DataArray implements \ArrayAccess, \Countable, \IteratorAggregate
{
	/** @var \ArrayObject */
	protected $data;

	function __construct(\ArrayObject $data = null)
	{
		$this->data = $data ? $data : new \ArrayObject();
	}

	/**
	 * Looks for record by Id keys
	 * @param int $id
	 * @return DataHash|null
	 */
	public function find($id)
	{
		return isset($this->data[$id]) ? $this->data[$id] : null;
	}

	/**
	 * Sort by column name
	 * @param mixed $column
	 * @param bool $ascending
	 * @return bool    Information about the sort of success
	 */
	public function sortBy($column, $ascending = true)
	{
		$sortBy = array();
		foreach ($this->data as $key => $row) {
			if (!isset($row[$column])) return false;
			$sortBy[$key] = $row[$column];
		}
		$array = $this->data->getArrayCopy();
		array_multisort($sortBy, $ascending ? SORT_ASC : SORT_DESC, $array);
		$this->data = new \ArrayObject($array);
		return true;
	}

	/**
	 * Returns associative array for form select list
	 * @param string|array $valueProperty Property name, which is to list as a value, or an array of properties that are to appear in the specified order with the applied format
	 * @param string $format The format for function vsprintf, which said how to compose properties
	 * @return array
	 */
	public function toList($valueProperty = 'name', $format = '%s')
	{
		$props = is_array($valueProperty) ? $valueProperty : array($valueProperty);

		$select = array();
		foreach ($this->data as $row) {
			$values = array();
			foreach ($props as $key => $propName) {
				$values[$key] = $row[$propName];
			}
			$select[(string)$row->id] = vsprintf($format, $values);
		}
		return $select;
	}

	/**
	 * Iterator pro foreach
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return $this->data->getIterator();
	}

	/**
	 * Ger first element
	 * @return mixed|false
	 */
	function getFirst()
	{
		$iterator = $this->getIterator();
		$iterator->rewind();
		return $iterator->current();
	}

	/**
	 * Database result
	 * @return DataHash[]|\ArrayObject
	 */
	function getData()
	{
		return $this->data;
	}

	/**
	 * Returns a item.
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return $this->data->offsetExists($offset);
	}

	/**
	 * Returns a item.
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->data->offsetGet($offset);
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return $this
	 */
	public function offsetSet($offset, $value)
	{
		$this->data->offsetSet($offset, $value);
		return $this;
	}

	/**
	 * Removes the element from this list.
	 * @param mixed $offset
	 * @return $this
	 */
	public function offsetUnset($offset)
	{
		$this->data->offsetUnset($offset);
		return $this;
	}

	/**
	 * Returns items count.
	 * @return int
	 */
	public function count()
	{
		return $this->data->count();
	}
}
