<?php

//namespace = ?;
trait CallMethod
{

    public static function __callStatic($method, $args)
    {
        $class = get_called_class();
        $instance = new $class;
        return call_user_func_array(array($instance, $method), $args);

    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this, $method), $args);
    }
}
