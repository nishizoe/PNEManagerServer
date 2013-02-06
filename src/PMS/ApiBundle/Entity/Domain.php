<?php

namespace PMS\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PMS\ApiBundle\Entity\Domain
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PMS\ApiBundle\Entity\DomainRepository")
 */
class Domain
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
     * @var string $domain
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

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
     * Set domain
     *
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set type
     *
     * @param string $domain
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
