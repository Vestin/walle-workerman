<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 4:14 PM
 */

namespace app\component;


interface WalleRequestInterface
{

    /**
     * request data
     * WalleRequestInterface constructor.
     * @param $data
     */
    public function __construct($data);

    public function getMethod();

    public function getUri();

}