<?php
/**
 * Created by PhpStorm.
 * User: vestin
 * Date: 6/13/17
 * Time: 10:01 AM
 */

namespace app\component;


class ControllerDispatcher
{

    /**
     * @param \Closure | string ClassName @method $handler
     * @param $data
     */
    public function dispatch($handler, $params)
    {
        if (is_callable($handler)) {
            return $handler();
        }

        $arr = explode('@', $handler);
        if (count($arr) != 2) {
            throw new HandleNotFoundException();
        }

        $className = 'app\\controller\\' . $arr[0];
        $method = $arr[1];
        if (!class_exists($className)) {
            throw new HandleNotFoundException('ClassName:' . $className . ' NOT EXISTS');
        }
        $class = new $className();
        if (!method_exists($class, $method)) {
            throw new HandleNotFoundException('ClassName:' . $className . ' Method:' . $method . ' NOT EXISTS');
        }

        $args = $this->bindActionParams($class, $method, $params);
        return call_user_func_array([$class, $method], $args);
    }


    public function bindActionParams($class, $method, $params)
    {
        $method = new \ReflectionMethod($class, $method);

        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array)$params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new HandleParamsErrorException("Invalid data received for parameter '{param}'");
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            $str = json_encode($missing);
            throw new HandleParamsErrorException("Missing required parameters: '{$str}'");
        }

        return $args;
    }

}