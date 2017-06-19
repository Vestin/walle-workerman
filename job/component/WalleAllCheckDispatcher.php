<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/19/17
 * Time: 4:42 PM
 */

namespace job\component;


use Monolog\Logger;
use Vestin\Checker\CheckerInterface;
use Vestin\Checker\CheckNotPassException;
use Vestin\Checker\Dispatchers\AllCheckDispatcher;

class WalleAllCheckDispatcher extends AllCheckDispatcher
{
    /**
     * @var CheckerInterface[] array
     */
    protected $checkers;
    protected $errors;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * WalleAllCheckDispatcher constructor.
     * @param Logger $logger
     */
    public function __construct()
    {
        global $jobHandle;
        $this->logger = $jobHandle->logger;
    }

    public function addChecker(CheckerInterface $checker)
    {
        $this->logger->info('add checker:' . get_class($checker));
        $this->checkers[] = $checker;
    }

    public function check()
    {
        $result = true;
        foreach ($this->checkers as $checker) {
            try {
                $this->logger->info('checking '.get_class($checker).' ...');
                $checker->check();
            } catch (CheckNotPassException $e) {
                $this->logger->error('checking FAILED '.get_class($checker).' ...');
                $this->errors[] = $e->getMessage();
                $result = false;
            }
        }

        return $result;
    }


}