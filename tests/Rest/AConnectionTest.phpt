<?php

use Nette\Http\Url;
use Pipas\Rest\AConnection;
use Pipas\Rest\Configuration;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class FakeConnection extends AConnection
{

	function sendGet(Url $url)
	{
	}

	function sendPost(Url $url, array $data)
	{
	}

	function sendPut(Url $url, array $data)
	{
	}

	function sendDelete(Url $url)
	{
	}

	function checkConnection()
	{
	}
}

//Description
test(function () {
	$config = new Configuration("id", "user", "pass", "seznam.cz");
	$connection = new FakeConnection($config);

	Assert::equal($config, $connection->getConfiguration());
});

