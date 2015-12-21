<?php


namespace Pipas\Rest\Debug;

use Nette\Http\Url;
use Nette\Object;
use Tracy\Dumper;

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

	/**
	 * Create log ans start timing
	 * @param string $type
	 * @param Url|string $url
	 * @param array $params
	 */
	public function __construct($type, $url, array $params = null)
	{
		$this->type = $type;
		$this->params = $params;
		if ($url instanceof Url) {
			$this->params = $url->queryParameters;
			$url->setQuery("");
		}
		$this->url = (string)$url;
		$this->start = microtime(true);
	}

	/**
	 * Exits the measurement and write the result
	 * @param $result
	 */
	public function end($result = null)
	{
		$this->result = $result;
		$this->end = microtime(true);
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
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return mixed
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
	 * @return string
	 */
	public function getParamsAsHtml()
	{
		return Dumper::toHtml($this->params);
	}

	/**
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * @return string
	 */
	public function getResultAsHtml()
	{
		return Dumper::toHtml($this->result);
	}
}