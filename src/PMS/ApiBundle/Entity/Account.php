<?php

namespace PMS\ApiBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

use PMS\ApiBundle\Entity\Sns;

/**
 * PMS\ApiBundle\Entity\Account
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Account implements UserInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\OneToMany(targetEntity="Sns", mappedBy="account")
     */
    private $snss;

    public function __construct()
    {
        $this->password = '';
        $this->salt = '';
    }

    /**
     * get id
     *
     * @return intetger
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setUsername($string)
    {
    }

    public function getUsername()
    {
    }

    /**
     * set password
     *
     * @return string
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
       return array('ROLE_USER');
    }

    public function setSalt($salt)
    {
       $this->salt = $salt;
    }

    public function getSalt()
    {
       return $salt;
    }

    public function eraseCredentials()
    {
    }

    public function equals(UserInterface $account)
    {
    }

}
