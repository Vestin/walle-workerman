<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/12/17
 * Time: 4:14 PM
 */

namespace app\component;


class WalleRequest implements WalleRequestInterface
{
    private $data;

    /**
     * [
     *  'method'=>'GET,POST,DELETE,PUT',
     *  'uri'=>'/users/id/1',
     *  'data'=>['key'=>'value','key'=>'value']
     * ]
     * @var array
     */
    private $requestData = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->_parseData();
    }

    private function _parseData()
    {
        $requestData = json_decode($this->data, true);
        if ($requestData === false || $requestData == '') {
            throw new BadRequestException('error data');
        }
        $this->requestData = $requestData;
    }

    public function getMethod()
    {
        if (!isset($this->requestData['method'])) {
            return 'GET';
        }
        return strtoupper($this->requestData['method']);
    }

    public function getUri()
    {
        if (!isset($this->requestData['uri'])) {
            return '';
        }
        return $this->requestData['uri'];
    }

    public function getParams()
    {
        if (isset($this->requestData['params'])) {
            return $this->requestData['params'];
        } else {
            return [];
        }
    }
}