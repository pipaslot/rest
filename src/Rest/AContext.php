<?php

namespace Pipas\Rest;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Pipas\Rest\Result\ResultMapper;

/**
 * Basic context for connection to API
 *
 * @author Petr Stipek <p.stipek@email.cz>
 */
abstract class AContext implements IContext
{
	/** @var array Service mapping */
	private $mappings = array();
	private $services = array();

	/**
	 * @var Cache
	 */
	protected $cache;

	/**
	 * @var IConnection
	 */
	protected $driver;

	/** @var  ResultMapper */
	protected $resultMapper;

	function __construct(IConnection $driver, IStorage $cacheStorage)
	{
		$this->driver = $driver;
		$this->cache = new Cache($cacheStorage, get_called_class());
		$this->resultMapper = ResultMapper::get();
	}

	/**
	 * Define mapping for auto-loading of services
	 * @param string $namespace
	 * @return $this
	 * @throws \OutOfRangeException
	 */
	function addServiceMapping($namespace)
	{
		if (($star = strpos($namespace, "*")) === false) throw new \OutOfRangeException("Namespace must have char '*' used for mapping");
		if (strpos($namespace, "*", $star + 1) !== false) throw new \OutOfRangeException("Only one char '*' is supported");
		$this->mappings[$namespace] = $namespace;
		return $this;
	}


	/**
	 * Return drive for connection to the API via REST
	 * @return IConnection
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 *
	 * @param string $name
	 * @return IService
	 */
	public function getService($name)
	{
		if (!isset($this->services[$name])) {
			$attempts = array();
			foreach ($this->mappings as $mapping) {
				$class = str_replace("*", ucfirst($name), $mapping);
				$attempts[] = $class;
				if (class_exists($class)) {
					$this->services[$name] = new $class($this);
					break;
				}
			}
			if (empty($this->services[$name])) throw new \OutOfRangeException("Cannot load service with name:'$name' from choices[" . implode(', ', $attempts) . "]. Please check if you have correctly setup mapping");
		}
		return $this->services[$name];
	}


	public function __get($name)
	{
		return $this->getService($name);
	}

}
