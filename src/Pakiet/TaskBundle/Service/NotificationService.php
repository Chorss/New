<?php

namespace Pakiet\TaskBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class NotificationService
{
    /**
     * @var Session
     */
    private $session;

    /**
     * NotificationService constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Add message
     *
     * @param String $type      Type message
     * @param String $message   The message
     *
     * @return String Message
     */
    private function addMessage($type, $message)
    {
        return $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * Return message in FlashBag
     *
     * @param String $message  The message
     *
     * @return string   Message
     */
    public function addSuccess($message)
    {
        return $this->addMessage("success", $message);
    }

    /**
     * Return message in FlashBag
     *
     * @param String $message  The message
     *
     * @return String   Message
     */
    public function addError($message)
    {
        return $this->addMessage("danger", $message);
    }
}