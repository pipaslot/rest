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
	 * Send GET request to receive the data
	 * @param string $path Path to service
	 * @param array $query
	 * @return mixed
	 */
	function sendGet($path, $query = array());

	/**
	 * Update record by POST request
	 * @param string $path Path to service
	 * @param mixed $data
	 * @return mixed
	 */
	function sendPost($path, array $data);

	/**
	 * Create new record by PUT request
	 * @param string $path Path to service
	 * @param mixed $data
	 * @return mixed
	 */
	function sendPut($path, array $data);

	/**
	 * Delete data by DELETE request
	 * @param string $path Path to service
	 * @param array $query
	 * @return mixed
	 */
	function sendDelete($path, $query = array());

	/**
	 * Constructing URL for API communication
	 * @param string $path Path to service
	 * @param array $query
	 * @return Url
	 */
	function buildUrl($path, $query = array());

	/**
	 * Check if the connection is working
	 * @return bool
	 */
	function checkConnection();
}
