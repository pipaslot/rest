<?php

namespace Pipas\Rest\Cache;

use Nette\Caching\IStorage;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ArrayStorage implements IStorage
{
	private $data = array();

	public function clean(array $conditions)
	{
		$this->data = array();
	}

	public function lock($key)
	{

	}

	public function read($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	public function remove($key)
	{
		if (isset($this->data[$key])) {
			unset($this->data[$key]);
		}
	}

	public function write($key, $data, array $dependencies = array())
	{
		$this->data[$key] = $data;
	}

}