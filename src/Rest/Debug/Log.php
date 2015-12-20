<?php


namespace Pipas\Rest\Debug;

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
	 * @param $type
	 * @param $url
	 * @param $params
	 */
	public function __construct($type, $url, $params = null)
	{
		$this->type = $type;
		$this->url = $url;
		$this->params = $params;
		$this->start = microtime(true);
	}

	/**
	 * Set result and stop timing
	 * @param $data
	 */
	public function setResult($data = null)
	{
		$this->result = $data;
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