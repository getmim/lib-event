<?php
/**
 * Event binder for worker
 * @package lib-event
 * @version 0.0.1
 */

namespace LibEvent\Controller;

use LibEvent\Library\Event;

class EventController extends \Cli\Controller
{
    public function bindAction(){
        $event = $this->worker->getData();
        Event::bind($event->name, $event->data);
        $this->worker->setResult('success');
    }
}