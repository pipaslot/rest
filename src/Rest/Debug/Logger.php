<?php


namespace Pipas\Rest\Debug;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class Logger
{
	private static $logs = array();

	/**
	 * Logs POST request
	 * @param $uri
	 * @param array $params
	 * @return Log
	 */
	public static function startPost($uri, array $params = array())
	{
		return self::$logs[] = new Log(Log::POST, $uri, $params);
	}

	/**
	 * Logs PUT request
	 * @param $uri
	 * @param array $params
	 * @return Log
	 */
	public static function startPut($uri, array $params = array())
	{
		return self::$logs[] = new Log(Log::PUT, $uri, $params);
	}

	/**
	 * Logs GET request
	 * @param $uri
	 * @param array $params
	 * @return Log
	 */
	public static function startGet($uri, array $params = array())
	{
		return self::$logs[] = new Log(Log::GET, $uri, $params);
	}

	/**
	 * Logs DELETE request
	 * @param $uri
	 * @param array $params
	 * @return Log
	 */
	public static function startDelete($uri, array $params = array())
	{
		return self::$logs[] = new Log(Log::DELETE, $uri, $params);
	}

	/**
	 * @return Log[]
	 */
	public static function getList()
	{
		return self::$logs;
	}
}