<?php

use Nette\Http\Url;
use Pipas\Rest\Debug\Log;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

//Default plain construction
test(function () {
	$type = "GET";
	$url = new Url("myurl");
	$result = array("my", "result");
	$log = new Log($type, $url);
	Assert::null($log->getTimeDelta());

	$log->end($result);

	Assert::equal($type, $log->getType());
	Assert::same($url, $log->getUrl());
	Assert::equal($result, $log->getResult());
	Assert::true($log->getTimeDelta() > 0);
});
//PUT and POST parameters passing
test(function () {
	$data = array("one", "two");
	$params = array("first", "second");
	$url = new Url("my-url.com");;
	$url->setQuery($params);

	$log1 = new Log(Log::PUT, $url, $data);
	Assert::equal($data, $log1->getParams());
	$log2 = new Log(Log::POST, $url, $data);
	Assert::equal($data, $log2->getParams());
	$log3 = new Log(Log::GET, $url, $data);
	Assert::equal($params, $log3->getParams());
	$log4 = new Log(Log::DELETE, $url, $data);
	Assert::equal($params, $log4->getParams());
});


//Clone URL object for the prevention of adjustments reflecting changes in the log
test(function () {
	$params = array("first" => 1, "second" => 2);
	$url = new Url("http://my-url.com");
	$urlWithParams = clone $url;
	$urlWithParams->setQuery($params);
	$hash = $url->__toString();

	$log = new Log("GET", $url);

	Assert::equal($url->__toString(), $log->getUrl()->__toString());// Remove query parameters from log address
	Assert::equal($hash, $url->__toString());//Check that default object was not changed
});

