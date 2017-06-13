<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 4:47 PM
 */

namespace app\component;


class WalleResponse
{
    private $connection;

    /**
     * WalleResponse constructor.
     * @param $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function send(array $data){
        $this->connection->send(json_encode($data));
    }
}