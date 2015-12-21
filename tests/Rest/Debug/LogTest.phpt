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

	Assert::true(is_string($log->getParamsAsHtml()));
	Assert::true(is_string($log->getResultAsHtml()));
});

//Passing URL object
test(function () {
	$url = new Url("myurl");
	$params = array("one", "two");
	$log = new Log("GET", $url, $params);

	Assert::equal($url->getAbsoluteUrl(), $log->getUrl());
	Assert::equal(array(), $log->getParams());
});

//Passing URL object with parameters
test(function () {
	$url = new Url("myurl");
	$url->setQuery("first=1&second=2");
	$log = new Log("GET", $url);

	Assert::equal($url->getAbsoluteUrl(), $log->getUrl());
	Assert::equal(array("first" => '1', "second" => '2'), $log->getParams());
});

