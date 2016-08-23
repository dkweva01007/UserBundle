<?php

namespace DB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class ClientName {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cleint_id", referencedColumnName="id")
     * })
     * @Expose
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Expose
     */
    private $name;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Reservation
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set clientId
     *
     * @param \DB\UserBundle\Entity\ClientId $clientId
     * @return Reservation
     */
    public function setClientId(\DB\UserBundle\Entity\ClientId $clientId = null) {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return \DB\UserBundle\Entity\ClientId 
     */
    public function getClientId() {
        return $this->clientId;
    }

}
