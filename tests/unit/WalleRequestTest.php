<?php


class WalleRequestTest extends \Codeception\Test\Unit
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
    public function testSomeFeature()
    {
        $WalleRequest = new \app\component\WalleRequest(json_encode(['method'=>'get','uri'=>'/users/id/1','data'=>['key'=>'value']]));
        $this->assertEquals($WalleRequest->getMethod(),'get');
        $this->assertEquals($WalleRequest->getUri(),'/users/id/1');
    }
}