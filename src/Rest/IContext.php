<?php

namespace Pipas\Rest;

/**
 * Context designed for connection to remote tables
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IContext
{

	/**
	 * Define mapping for auto-loading of services
	 * @example 'MyRest/Services/*Service'
	 * @param $namespace
	 * @return $this
	 */
	function addServiceMapping($namespace);

	/**
	 * Return drive for connection to the API via REST
	 * @return IConnection
	 */
	function getDriver();

	/**
	 * Returns instance of repository under this context
	 * @param string $name
	 * @return IService
	 */
	function getService($name);

	/**
	 * Validate the short time token
	 * @param string $token
	 * @return bool
	 */
	function validateToken($token);

}
