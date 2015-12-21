<?php

use Nette\Http\Url;
use Pipas\Rest\Configuration;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$instanceName = "instance";
	$userEmail = "email";
	$apiKey = "key";
	$apiUrl = "http://my.domain.com/api";
	$conn = new Configuration($instanceName, $userEmail, $apiKey, $apiUrl);

	Assert::equal($instanceName, $conn->getInstanceName());
	Assert::equal($userEmail, $conn->getUserName());
	Assert::equal($apiKey, $conn->getApiKey());
	Assert::true($conn->getApiUrl() instanceof Url);
	Assert::equal($apiUrl, $conn->getApiUrl()->absoluteUrl);
});
//Test scheme appending if not exist
test(function () {
	$apiUrl = "//my.domain.com/api";
	$conn = new Configuration("instance", "email", "key", $apiUrl);

	Assert::equal("http:" . $apiUrl, $conn->getApiUrl()->absoluteUrl);
	Assert::equal("http", $conn->getApiUrl()->scheme);
});
test(function () {
	$url = "my.domain.com/api";
	$apiUrl = new Url($url);
	$conn = new Configuration("instance", "email", "key", $apiUrl);

	Assert::equal("http://" . $url, $conn->getApiUrl()->absoluteUrl);
	Assert::equal("http", $conn->getApiUrl()->scheme);
});
test(function () {
	$url = "https://my.domain.com/api";
	$apiUrl = new Url($url);
	$conn = new Configuration("instance", "email", "key", $apiUrl);

	Assert::equal($url, $conn->getApiUrl()->absoluteUrl);
	Assert::equal("https", $conn->getApiUrl()->scheme);
});