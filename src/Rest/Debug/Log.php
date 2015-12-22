<?php


namespace Pipas\Rest\Debug;

use Nette\Http\Url;
use Nette\Object;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class Log extends Object
{
	const POST = 'POST',
		PUT = 'PUT',
		GET = 'GET',
		DELETE = 'DELETE';

	private $type;
	private $url;
	private $params;
	private $result;
	private $start;
	private $end;
	private $cached = false;

	/**
	 * Create log ans start timing
	 * @param string $type
	 * @param Url|string $url
	 * @param array $data POST and PUT data
	 */
	public function __construct($type, Url $url, array $data = null)
	{
		$this->type = strtoupper($type);
		$this->url = $url;
		if ($data AND ($this->type == self::POST OR $this->type == self::PUT)) {
			$this->params = $data;
		} else {
			$this->params = $this->url->queryParameters;
		}
		$this->start = microtime(true);
	}

	/**
	 * Exits the measurement and write the result
	 * @param mixed $result
	 * @param bool $isCached
	 * @return mixed Result
	 */
	public function end($result = null, $isCached = false)
	{
		$this->result = $result;
		$this->end = microtime(true);
		$this->setCached($isCached);
		return $result;
	}

	/**
	 * Result was taken from cache
	 * @param bool $cached
	 * @return $this
	 */
	public function setCached($cached = true)
	{
		$this->cached = (bool)$cached;
		return $this;
	}

	/**
	 * Result war taken from cache
	 * @return bool
	 */
	public function isCached()
	{
		return $this->cached;
	}

	/**
	 * Get latency between constructor and setResult
	 * @return int
	 */
	public function getTimeDelta()
	{
		return (isset($this->start) AND isset($this->end)) ? $this->end - $this->start : null;
	}

	/*********************************** Getters ****************************************/
	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return Url
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return mixed
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}
}