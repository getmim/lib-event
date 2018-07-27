<?php
/**
 * Event registerer
 * @package lib-event
 * @version 0.0.1
 */

namespace LibEvent\Library;

use LibWorker\Library\Worker;

class Event
{
    static function trigger(string $name, $data=null): void{
        // call it now, or use worker
        if(!module_exists('lib-worker')){
            self::bind($name, $data);
            return;
        }

        Worker::add($name, [
            'route' => [
                'name' => 'toolEventBind'
            ],
            'data' => (object)[
                'name' => $name,
                'data' => $data
            ],
            'time' => 0,
            'loop' => 1
        ]);
    }

    static function bind(string $name, $data=null): void{
        $event = \Mim::$app->config->libEvent->events;
        if(!isset($event->$name))
            return;
        $listeners = $event->$name;
        foreach($listeners as $handler => $use){
            if(!$use)
                continue;
            $hdrs = explode('::', $handler);
            $class = $hdrs[0];
            $method= $hdrs[1];

            $class::$method($data);
        }
    }
}