<?php

namespace PMS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use PMS\ApiBundle\Entity\Sns;

/**
 * PMS\ApiBundle\Entity\SnsPasswords
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SnsPasswords
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
     * @var string $memberPassword
     *
     * @ORM\Column(name="memberPassword", type="string", length=255)
     */
    private $memberPassword;
    
    /**
     * @var string $adminPassword
     *
     * @ORM\Column(name="adminPassword", type="string", length=255)
     */
    private $adminPassword;
    
    /**
     * @var integer $snsId
     *
     * @ORM\Column(name="sns_id", type="integer")
     */
    private $snsId;
    
    public function getId()
    {
        return $this->id;
    }

    public function setMemberPassword($memberPassword)
    {
        $this->memberPassword = $memberPassword;
    }

    public function getMemberPassword()
    {
        return $this->memberPassword;
    }

    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
    }
    
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    public function setSnsId($snsId)
    {
        $this->snsId = $snsId;
    }

    public function getSnsId()
    {
        return $this->snsId;
    }
    
}
