<?php

namespace Pipas\Rest;

use Nette\Object;

/**
 * Basic context for connection to API
 *
 * @author Petr Stipek <p.stipek@email.cz>
 *
 * @property-read IDriver $driver
 */
abstract class AContext extends Object implements IContext
{
	/** @var array Service mapping */
	private $mappings = array();
	private $services = array();

	/** @var IDriver */
	protected $driver;

	function __construct(IDriver $driver)
	{
		$this->driver = $driver;
	}

	/**
	 * Return drive for connection to the API via REST
	 * @return IDriver
	 */
	public function getDriver()
	{
		return $this->driver;
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
	 * Returns instance of repository under this context
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
