<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/15/17
 * Time: 5:37 PM
 */

namespace app\model\basic;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';

    /**
     * find user by Token
     * @param $token
     * @return mixed
     */
    static public function findByToken($token)
    {
        return self::where('ws_token',$token)->first();
    }

}