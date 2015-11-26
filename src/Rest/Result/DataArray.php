<?php

namespace Pipas\Rest\Result;

/**
 * Pole pro iteraci nad objekty
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
class DataArray implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /** @var DataHash[] */
    protected $data;

    function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Najde záznam podle Id klíče
     * @param int $id
     * @return DataHash|null
     */
    public function find($id)
    {
        return isset($this->data[$id]) ? $this->data[$id] : null;
    }

    /**
     * Seřadí prvky podle specifického sloupce položek
     * @param mixed $column
     * @param bool $ascending Vzestupné řazení
     * @return bool Informace, zda řazení proběhlo v pořádku
     */
    public function sortBy($column, $ascending = true)
    {
        $sortBy = array();
        foreach ($this->data as $key => $row) {
            if (!isset($row[$column])) return false;
            $sortBy[$key] = $row[$column];
        }
        array_multisort($sortBy, $ascending ? SORT_ASC : SORT_DESC, $this->data);

        return true;
    }

    /**
     * Vrátí asociativní pole klíče a hodnoty pro selectList
     * @param string|array $valueProperty - název vlastnosti, která se má vypsat jako hodnota, nebo pole vlastností které se mají zobrazit v zadaném pořadí s aplikovaným formátem
     * @param string $format - Formát pro vsprint, který říká jak se zadané $valueProperty budou skládat do stringu
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
            $select[$row->id] = vsprintf($format, $values);
        }
        return $select;
    }

    /**
     * Iterator pro foreach
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Ger first element
     * @return mixed|false
     */
    function getFirst()
    {
        return reset($this->data);
    }

    /**
     * Database result
     * @return DataHash
     */
    function getData()
    {
        return $this->data;
    }

    /**
     * Returns a item.
     * @return mixed
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns a item.
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Removes the element from this list.
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Returns items count.
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }
}
