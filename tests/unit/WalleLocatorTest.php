<?php


class WalleLocatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testWalleLocatorFeature()
    {
        $Locator = new \app\component\WalleLocator();
        $handler = $Locator->getHandlerForCommand(new \tests\command\OkCommand());
        $this->assertEquals($handler,'app\\handler\\OkCommandHandler');
    }
}