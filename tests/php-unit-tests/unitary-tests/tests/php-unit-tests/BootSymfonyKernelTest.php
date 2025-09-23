<?php


namespace Combodo\iTop\Test\UnitTest\Sources\Controller;

use Combodo\iTop\Portal\Controller\AggregatePageBrickController;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class BootSymfonyKernelTest extends ItopDataTestCase
{
	public function testInstantiateServiceWithAnExplicitCallToBootTheKernel()
	{
		$this->SetKernelClass(\Combodo\iTop\Portal\Kernel::class);
		self::bootKernel();
		$controller = static::getContainer()->get(AggregatePageBrickController::class);

		$this->assertInstanceOf(AggregatePageBrickController::class, $controller);
	}

	public function testInstantiateServiceWithAnAutomaticKernelBoot()
	{
		$this->SetKernelClass(\Combodo\iTop\Portal\Kernel::class);
		$controller = static::getContainer()->get(AggregatePageBrickController::class);

		$this->assertInstanceOf(AggregatePageBrickController::class, $controller);
	}

	public function testUnspecifiedKernelClassThrowsAnException()
	{
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessage('static::SetKernelClass() must be called before booting the kernel');

		static::getContainer();
	}

	public function testTwoDifferentKernelsCanBeStartedConsecutively()
	{
		self::markTestSkipped('This test is still failing: the second kernel container does not find the requested service');

		$this->SetKernelClass(\Combodo\iTop\Kernel::class);
		self::bootKernel();

		$this->SetKernelClass(\Combodo\iTop\Portal\Kernel::class);
		self::bootKernel();
		$controller = static::getContainer()->get(AggregatePageBrickController::class);

		$this->assertInstanceOf(AggregatePageBrickController::class, $controller);
	}
}
