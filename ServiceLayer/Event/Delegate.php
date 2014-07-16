<?php

namespace Event;

/**
 * Description of _Delegate
 *
 * @author Administrador
 */
class Delegate {

    private $storage;

    /**
     * Add to storage a event
     * @param \Event\AbstractEvent $event
     * @return \Event\Delegate
     */
    public function addEvent(AbstractEvent $event) {
        $this->storage[] = $event;
        return $this;
    }

    /**
     * Listening to a Client
     * @see \Event\AbstractEvent::listen
     * @return null
     * @throws \RuntimeException
     */
    public function runEvents() {
        foreach ($this->storage as $eventControll) {
            if ($eventControll->has()) {
                $eventControll->listen();
                return null;
            }
        }
        throw new \RuntimeException('Event not Found!');
    }

}
