<?php

namespace Pipas\Rest;

use Nette\Utils\Strings;
use Pipas\Rest\Result\IResultMapper;
use Pipas\Rest\Result\ResultMapper;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class ABaseService implements IBaseService
{
	/** @var ResultMapper */
	protected $resultMapper;
	/** @var IDriver */
	protected $driver;

	function __construct(IDriver $driver, IResultMapper $resultMapper = null)
	{
		$this->driver = $driver;
		$this->resultMapper = $resultMapper == null ? ResultMapper::create() : $resultMapper;
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