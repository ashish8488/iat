<?php

namespace IATBundle\Service;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class CustomPasswordEncoder extends BasePasswordEncoder
{
    public function __construct()
    {
    }

    public function encodePassword($password, $salt)
    {
        $iv = mcrypt_create_iv(32);
        $encyptedPassword = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $password, MCRYPT_MODE_ECB, $iv);
        $encyptedPassword = base64_encode($encyptedPassword);

        return $encyptedPassword;
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        //echo $encoded.'====='.$this->encodePassword($raw, $salt); die;
        return $encoded === $this->encodePassword($raw, $salt);
    }
}
