<?php

use Nette\Http\Url;
use Pipas\Rest\Debug\Log;
use Pipas\Rest\Debug\Logger;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

//Description
test(function () {

	$list[] = $log1 = Logger::startGet(new Url("url"));
	Assert::true($log1 instanceof Log);
	Assert::equal(Log::GET, $log1->getType());

	$list[] = $log2 = Logger::startPut(new Url("url2"));
	Assert::true($log2 instanceof Log);
	Assert::equal(Log::PUT, $log2->getType());

	$list[] = $log3 = Logger::startPost(new Url("url2"));
	Assert::true($log3 instanceof Log);
	Assert::equal(Log::POST, $log3->getType());

	$list[] = $log4 = Logger::startDelete(new Url("url2"));
	Assert::true($log4 instanceof Log);
	Assert::equal(Log::DELETE, $log4->getType());

	Assert::equal($list, Logger::getList());

	Logger::clean();
	Assert::equal(array(), Logger::getList());
});

