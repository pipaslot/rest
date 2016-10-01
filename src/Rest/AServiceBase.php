<?php

namespace Pipas\Rest;

use Nette\Utils\Strings;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class AServiceBase implements IServiceBase
{
	/** @var IDriver */
	protected $driver;

	function __construct(IDriver $driver)
	{
		$this->driver = $driver;
	}

	/**
	 * Returns target service name<br>
	 * As default it convert name of class 'MyExtraService' to 'myExtra'
	 * @return string
	 */
	public function getName()
	{
		$class = get_called_class();
		$slashPos = strrpos($class, "\\");
		$className = trim(substr($class, $slashPos), "\\");
		if (Strings::endsWith($className, self::NAME_SUFFIX)) {
			$className = substr($className, 0, strlen($className) - strlen(self::NAME_SUFFIX));
		}
		return lcfirst($className);
	}
}