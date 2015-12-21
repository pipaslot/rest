<?php


namespace Pipas\Rest\Debug;

use Nette\Http\Url;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class Logger
{
	private static $logs = array();

	/**
	 * Logs POST request
	 * @param Url $url
	 * @param array $data
	 * @return Log
	 */
	public static function startPost(Url $url, array $data = array())
	{
		return self::$logs[] = new Log(Log::POST, $url, $data);
	}

	/**
	 * Logs PUT request
	 * @param Url $url
	 * @param array $data
	 * @return Log
	 */
	public static function startPut(Url $url, array $data = array())
	{
		return self::$logs[] = new Log(Log::PUT, $url, $data);
	}

	/**
	 * Logs GET request
	 * @param Url $url
	 * @return Log
	 */
	public static function startGet(Url $url)
	{
		return self::$logs[] = new Log(Log::GET, $url);
	}

	/**
	 * Logs DELETE request
	 * @param Url $url
	 * @return Log
	 */
	public static function startDelete($url)
	{
		return self::$logs[] = new Log(Log::DELETE, $url);
	}

	/**
	 * @return Log[]
	 */
	public static function getList()
	{
		return self::$logs;
	}

	/**
	 * Remove all logs
	 */
	public static function clean()
	{
		self::$logs = array();
	}
}