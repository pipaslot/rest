<?php

use Nette\Http\Url;
use Pipas\Rest\Debug\Log;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

//Default plain construction
test(function () {
	$type = "TYPE";
	$url = "myurl";
	$params = array("one", "two");
	$result = array("my", "result");
	$log = new Log($type, $url, $params);
	Assert::null($log->getTimeDelta());

	$log->end($result);

	Assert::equal($type, $log->getType());
	Assert::equal($url, $log->getUrl());
	Assert::equal($params, $log->getParams());
	Assert::equal($result, $log->getResult());
	Assert::true($log->getTimeDelta() > 0);

});

//Passing URL object
test(function () {
	$url = new Url("myurl");
	$params = array("one", "two");
	$log = new Log("GET", $url, $params);

	Assert::equal($url->getAbsoluteUrl(), $log->getUrl());
	Assert::equal(array(), $log->getParams());
});


//Clone URL object for the prevention of adjustments reflecting changes in the log
test(function () {
	$params = array("first" => 1, "second" => 2);
	$url = new Url("http://my-url.com");
	$urlWithParams = clone $url;
	$urlWithParams->setQuery($params);
	$hash = $url->__toString();
	dump($urlWithParams);
	$log = new Log("GET", $url);

	Assert::equal($params, $log->getParams()); //Gets parameters to log
	Assert::equal($url->__toString(), $log->getUrl());// Remove query parameters from log address
	Assert::equal($hash, $url->__toString());//Check that default object was not changed
});

