<?php

namespace Pipas\Rest;

use Nette\Http\Url;

/**
 * Class to control communication between the application and the REST API
 *
 * @author Petr Štipek <p.stipek@email.cz>
 */
interface IConnection
{
	/**
	 * @return Configuration
	 */
	function getConfiguration();

	/**
	 * Send GET request to receive the data
	 * @param Url $url
	 * @return mixed
	 */
	function sendGet(Url $url);

	/**
	 * Update record by POST request
	 * @param Url $url
	 * @param mixed $data
	 * @return mixed
	 */
	function sendPost(Url $url, array $data);

	/**
	 * Create new record by PUT request
	 * @param Url $url
	 * @param mixed $data
	 * @return mixed
	 */
	function sendPut(Url $url, array $data);

	/**
	 * Delete data by DELETE request
	 * @param Url $url
	 * @return mixed
	 */
	function sendDelete(Url $url);

	/**
	 * Check if the connection is working
	 * @return bool
	 */
	function checkConnection();
}
