<?php

namespace Client;

use Event\InterfaceEvent as EventManager;

/**
 * Description of AbstracClient
 *
 * @author Administrador
 */
abstract class AbstracClient implements ClientInterface {

    /**
     *
     * @var \Client\Delegate 
     */
    public $delegate;

    /**
     *
     * @var array get extras params 
     */
    public $params;
    public $key_cached = "";

    /**
     * Gets a delegate class to your event
     * @param \Client\Delegate $delegateClass
     * @return \Client\AbstracClient
     */
    public function clientDelegate(Delegate $delegateClass) {
        $this->delegate = $delegateClass;
        $this->params = \Main::$EXTRA_PARAMS;
        return $this;
    }

    /**
     * Dispatches event delegate class, execute controller.
     * @see \Client\Delegate::runClient
     * @param \Event\InterfaceEvent $subject
     */
    public function update(EventManager $subject) {
        $subject->dispatch()->delegate->runClient();
    }

}
