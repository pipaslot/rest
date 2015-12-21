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
	 * @param $url
	 * @param array $params
	 * @return Log
	 */
	public static function startPost($url, array $params = array())
	{
		return self::$logs[] = new Log(Log::POST, $url, $params);
	}

	/**
	 * Logs PUT request
	 * @param $url
	 * @param array $params
	 * @return Log
	 */
	public static function startPut($url, array $params = array())
	{
		return self::$logs[] = new Log(Log::PUT, $url, $params);
	}

	/**
	 * Logs GET request
	 * @param $url
	 * @param array $params
	 * @return Log
	 */
	public static function startGet($url, array $params = array())
	{
		return self::$logs[] = new Log(Log::GET, $url, $params);
	}

	/**
	 * Logs DELETE request
	 * @param $url
	 * @param array $params
	 * @return Log
	 */
	public static function startDelete($url, array $params = array())
	{
		return self::$logs[] = new Log(Log::DELETE, $url, $params);
	}

	/**
	 * @return Log[]
	 */
	public static function getList()
	{
		return self::$logs;
	}
}