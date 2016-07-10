<?php

namespace Package\UserBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as Templating;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Security\Core\Security;
use Package\UserBundle\Entity\User;

class UserManagerService
{
    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Templating
     */
    private $templating;

    /**
     * @var DataCollectorTranslator
     */
    private $translator;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    /**
     * @var UserMailerService
     */
    private $userMailer;

    function __construct(Doctrine $doctrine, Router $router, Templating $templating, DataCollectorTranslator $translator, UserPasswordEncoder $passwordEncoder, UserMailerService $user_mailer)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->passwordEncoder = $passwordEncoder;
        $this->userMailer = $user_mailer;
    }

    /**
     * Generate action token
     *
     * @return string
     */
    private function generateActionToken() {
        return substr(sha1(uniqid(NULL, TRUE)), 0, 30);
    }

    /**
     * Generate password
     *
     * @param $length - Length
     * @param bool|false $char - Char
     * @param bool|false $number - Number
     * @param bool|false $special - Special character
     * @return string - Password
     */
    private function generatePassword($length = 8, $char = false, $number = false, $special = false)
    {//TODO possible to improve method
        $text = "";

        if ($char) {
            $text .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }

        if ($number) {
            $text .= "0123456789";
        }

        if ($special) {
            $text .= "?><|:}{+_)(*&^%$#@!";
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $text[mt_rand(0, strlen($text))];
        }

        return $password;
    }

    public function sendResetPasswordLink($userEmail)
    {
        $em = $this->doctrine->getManager();

        $user = $em->getRepository('PackageUserBundle:User')
            ->findOneByEmail($userEmail);

        if($user === null) {
            throw new \Exception($this->translator->trans("User not found"));
        }

        try{
            $user->setActionToken($this->generateActionToken());
            $em->persist($user);
            $em->flush();
        }catch (\Exception $e){
            throw new \Exception($this->translator->trans("Problem with base"));
        }


        $urlParams = array(
            'actionToken' => $user->getActionToken()
        );

        $resetUrl = $this->router->generate('PackageUserBundle:Security:ResetPassword', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $emailBody = $this->templating->render('PackageUserBundle:Email:resetLink.html.twig', array(
            'resetUrl' => $resetUrl
        ));

        try{
            $this->userMailer
                ->setUser($user)
                ->setSubject($this->translator->trans("Link to reset your password"))
                ->setBody($emailBody)
                ->sendEmail();
        }catch (\Exception $e) {
            throw new \Exception($this->translator->trans("Problem with sending messages"));
        }
        return true;
    }

    public function resetPassword($actionToken)
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository('PackageUserBundle:User')
            ->findOneByActionToken($actionToken);

        if($user === null){
            throw new \Exception($this->translator->trans("Incorrect parameters action"));
        }

        $plainPassword = $this->generatePassword(10, true, true);
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);

        try{
            $user->setPassword($encodedPassword);
            $user->setActionToken(null);
            $em->persist($user);
            $em->flush();
        }catch (\Exception $e){
            throw new \Exception($this->translator->trans("Problem with base"));
        }

        $emailBody = $this->templating->render('PackageUserBundle:Email:newPassword.html.twig', array(
            'plainPassword' => $plainPassword
        ));

        try{
            $this->userMailer
                ->setSubject($this->translator->trans("New password"))
                ->setUser($user)
                ->setBody($emailBody)
                ->sendEmail();

        }catch (\Exception $e) {
            throw new \Exception($this->translator->trans("Problem with sending messages"));
        }
        return true;
    }

    public function registerUser(User $user)
    {
        if($user->getId() !== null) {
            throw new \Exception($this->translator->trans("The user you have already registered"));
        }

        try{
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(array('ROLE_USER'));
            $user->setActionToken($this->generateActionToken());

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }catch (\Exception $e){
            throw new \Exception($this->translator->trans("Problem with base"));
        }

        $urlParams = array(
            'actionToken' => $user->getActionToken()
        );
        $activationUrl = $this->router->generate('PackageUserBundle:Security:ActivateAccount', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $emailBody = $this->templating->render('PackageUserBundle:Email:accountActivation.html.twig', array(
            'activationUrl' => $activationUrl
        ));

        try{
            $this->userMailer
                ->setUser($user)
                ->setSubject($this->translator->trans("Activate account"))
                ->setBody($emailBody)
                ->sendEmail();
        }catch (\Exception $e){
            throw new \Exception($this->translator->trans("Problem with sending messages"));
        }
        return true;
    }

    public function activateAccount($actionToken)
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository('PackageUserBundle:User')
            ->findOneByActionToken($actionToken);

        if($user === null){
            throw new \Exception($this->translator->trans("Incorrect parameters action"));
        }

        try{
            $user->setIsEnabled(true);
            $user->setActionToken(null);
            $em->persist($user);
            $em->flush();
        }catch (\Exception $e){
            throw new \Exception($this->translator->trans("Problem with base"));
        }
        return true;
    }

    public function changePassword(User $user)
    {
        if($user->getPlainPassword() == null){
            throw new \Exception($this->translator->trans("Don't set a new password"));
        }

        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        
        try{
            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();
        }catch (\Exception $e) {
            throw new \Exception($this->translator->trans("Problem with base"));
        }
        return true;
    }
}