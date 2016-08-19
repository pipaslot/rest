<?php
namespace {

	require __DIR__ . '/../bootstrap.php';
}
namespace Fakes {

	use Pipas\Rest\AService;
	use Pipas\Rest\Result\Contract;

	class FakeCompanyService extends AService
	{
		protected function getContractName()
		{
			return Contract::class;
		}
	}
}

namespace Tests {

	use Mockery;
	use Pipas\Rest\AContext;
	use Pipas\Rest\AService;
	use Pipas\Rest\IDriver;
	use Pipas\Rest\Result\Contract;
	use Tester\Assert;
	use Tester\TestCase;


	class FakeContext extends AContext
	{

		function __construct(IDriver $driver)
		{
			parent::__construct($driver);
			$this->addServiceMapping("*Service");
		}

		public function find($serviceName, $id)
		{

		}

		public function findAll($serviceName)
		{

		}

		public function findBy($serviceName, array $query)
		{

		}

		public function findOneBy($serviceName, array $query = array())
		{

		}

		public function validateToken($token)
		{

		}

		public function create($serviceName, array $entity)
		{

		}

		public function delete($uri)
		{

		}

		public function update($serviceName, array $entity)
		{

		}

	}

	class FakeCompanyService extends AService
	{
		protected function getContractName()
		{
			return Contract::class;
		}
	}

	class FakeService extends AService
	{

		const SERVICE_NAME = "person";

		public function getName()
		{
			return self::SERVICE_NAME;
		}

		protected function getContractName()
		{
			return Contract::class;
		}
	}

	class AReadOnlyServiceTest extends TestCase
	{
		/** @var Mockery\MockInterface|IDriver */
		private $driver;

		function setUp()
		{
			parent::setUp();
			$this->driver = Mockery::mock(IDriver::class);
		}

		private function createRepository()
		{
			return new FakeService($this->driver);
		}

		function test_getServiceNameByClassName()
		{
			$rep = new FakeCompanyService($this->driver);
			Assert::equal("fakeCompany", $rep->getName());
		}

		function test_getServiceNameByClassNameUnderNamespace()
		{
			$rep = new \Fakes\FakeCompanyService($this->driver);
			Assert::equal("fakeCompany", $rep->getName());
		}

		function test_find_delegateToContext()
		{
			$id = 1;
			$this->driver->shouldReceive("find");
			$rep = $this->createRepository();
			$rep->find($id);
			$this->driver->shouldHaveReceived("find", array(FakeService::SERVICE_NAME, $id));
			Assert::true(true);
		}

		function test_findAll_delegateToContext()
		{
			$this->driver->shouldReceive("findAll");
			$rep = $this->createRepository();
			$rep->findAll();
			$this->driver->shouldHaveReceived("findAll", array(FakeService::SERVICE_NAME));
		}

		function test_findBy_delegateToContext()
		{
			$id = array("key" => "value");
			$this->driver->shouldReceive("findBy");
			$rep = $this->createRepository();
			$rep->findBy($id);
			$this->driver->shouldHaveReceived("findBy", array(FakeService::SERVICE_NAME, $id));
		}

		function test_findOneBy_delegateToContext()
		{
			$id = array("key" => "value");
			$this->driver->shouldReceive("findOneBy");
			$rep = $this->createRepository();
			$rep->findOneBy($id);
			$this->driver->shouldHaveReceived("findOneBy", array(FakeService::SERVICE_NAME, $id));
		}

	}

	$test = new AReadOnlyServiceTest();
	$test->run();
}
