<?php

namespace model;

use Illuminate\Database\Eloquent\Model;
use job\component\Command;

/**
 * This is the model class for table "record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $status
 * @property integer $action
 * @property integer $at
 * @property integer $duration
 * @property integer $memo
 */
class Record extends Model
{

    /**
     * 服务器权限检查
     */
    const ACTION_PERMSSION = 24;

    /**
     * 部署前置触发任务
     */
    const ACTION_PRE_DEPLOY = 40;

    /**
     * 本地代码更新
     */
    const ACTION_CLONE = 53;

    /**
     * 部署后置触发任务
     */
    const ACTION_POST_DEPLOY = 64;

    /**
     * 同步代码到服务器
     */
    const ACTION_SYNC  = 78;

    /**
     * 更新完所有目标机器时触发任务，最后一个得是100
     */
    const ACTION_UPDATE_REMOTE = 100;

    protected $table = 'record';

    public $timestamps = false;

    /**
     * 保存记录
     *
     * @param Command $commandObj
     * @param $task_id
     * @param $action
     * @param $duration
     * @return mixed
     */
    public static function saveRecord(Command $commandObj, $task_id, $action, $duration) {
        $record = new static();
        $record->attributes = [
            'user_id'    => '10086',
            'task_id'    => $task_id,
            'status'     => (int)$commandObj->getExeStatus(),
            'action'     => $action,
            'created_at' => time(),
            'command'    => var_export($commandObj->getExeCommand(), true),
            'memo'       => substr(var_export($commandObj->getExeLog(), true), 0, 65530),
            'duration'   => $duration,
        ];
        return $record->save();
    }

    /**
     * 获取耗时毫秒数
     *
     * @return int
     */
    public static function getMs()
    {
        return intval(microtime(true) * 1000);
    }
}
