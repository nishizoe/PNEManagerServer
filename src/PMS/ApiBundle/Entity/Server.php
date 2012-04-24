<?php

namespace PMS\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use PMS\ApiBundle\Entity\Sns;

/**
 * PMS\ApiBundle\Entity\Server
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Server
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
     * @var string $host
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @ORM\OneToMany(targetEntity="Sns", mappedBy="server")
     */
    private $snss;

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
     * Set host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * get snss
     *
     * @return ArrayCollection
     */
    public function getSnss()
    {
        return $this->snss;
    }

}
