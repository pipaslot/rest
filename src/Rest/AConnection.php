<?php


namespace Pipas\Rest;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
abstract class AConnection implements IConnection
{
	/** @var Configuration */
	protected $configuration;

	/**
	 * AConnection constructor.
	 * @param Configuration $configuration
	 */
	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
	}

	/**
	 * @return Configuration
	 */
	function getConfiguration()
	{
		return $this->configuration;
	}

}