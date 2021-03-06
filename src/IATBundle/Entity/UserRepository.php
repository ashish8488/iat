<?php

namespace IATBundle\Entity;

use IATBundle\Service\CustomPasswordEncoder;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
	
	/**
	 * @author Ashish Shah(ashish414@gmail.com)
	 * This function is used to save user
	 */
	public function setUser($userObject){
		$userObject->setType('merchant');
		$userObject->setFullName($userObject->getFirstName() . ' ' . $userObject->getLastName());
		$userObject->setSalt($this->getUserSalt());
		$userObject->setPassword($this->setUserPassword($userObject->getPassword(), $userObject->getSalt()));
		$em = $this->getEntityManager();
		$em->persist($userObject);
		$em->flush();
		return $userObject;
	}
	
	/**
	 * @author Ashish Shah(ashish414@gmail.com)
	 * This function is used to getUserSalt
	 * Function created to get User Salt
	 */
	public function getUserSalt()
	{
		$key = '';
		$characters = "1234567890abcdefghijklmnopqrstuvwxyz#*_-@";
		for ($i=0;$i<24;$i++) {
			$key .= $characters{rand(0, 40)};
		}
		return $key;
	}
	
	/**
	 * @author Ashish Shah(ashish414@gmail.com)
	 * Function created to generate encrypted User Password
	 */
	public function setUserPassword($encrypt, $key)
	{
		$encoder = new CustomPasswordEncoder();
		$encodedPassword = $encoder->encodePassword($encrypt, $key);
	
		return $encodedPassword;
	}
	
	// Decrypt Function
	/**
	 * @author Ashish Shah(ashish414@gmail.com)
	 * 
	 * Function created to decrypt User Password
	 */
	public function getUserPassword($decrypt, $key)
	{
		$iv = mcrypt_create_iv(32);
		$decoded = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($decrypt), MCRYPT_MODE_ECB, $iv));
		return $decoded;
	}
}
