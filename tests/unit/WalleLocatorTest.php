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
        $Locator = new \job\component\WalleLocator();
        $handler = $Locator->getHandlerForCommand(\tests\command\OkCommand::class);
        $this->assertEquals($handler,'job\\handler\\OkCommandHandler');
    }
}