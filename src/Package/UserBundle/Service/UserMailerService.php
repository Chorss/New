<?php

namespace Package\UserBundle\Service;

use Package\UserBundle\Entity\User;

class UserMailerService
{
    /**
     * @var \Swift_Mailer Swift Mailer
     */
    private $swiftMailer;

    /**
     * @var string From email
     */
    private $fromEmail;

    /**
     * @var string Form name
     */
    private $fromName;

    /**
     * @var string Subject
     */
    private $subject;

    /**
     * @var User User
     */
    private $user;

    /**
     * @var mixed Body
     */
    private $body;
//TODO dorobić dokumentacje
    function __construct(\Swift_Mailer $swiftMailer, $fromEmail, $fromName) {
        $this->swiftMailer = $swiftMailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    public function sendEmail()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($this->user->getEmail(), $this->user->getUsername())
            ->setBody($this->body, 'text/html');

        $this->swiftMailer->send($message);
    }

    /**
     * @param $fromEmail
     * @return $this
     */
    //options
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * @param $fromName
     * @return $this
     */
    //options
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @param $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
}