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

    /**
     * UserMailerService constructor.
     *
     * @param \Swift_Mailer $swiftMailer
     * @param $fromEmail string Form Email
     * @param $fromName string Name email
     */
    function __construct(\Swift_Mailer $swiftMailer, $fromEmail, $fromName) {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * Send email
     */
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
     * @param string $fromEmail
     * @return UserMailerService
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * @param string $fromName
     * @return UserMailerService
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @param string $subject
     * @return UserMailerService
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param User $user
     * @return UserMailerService
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param mixed $body
     * @return UserMailerService
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
}