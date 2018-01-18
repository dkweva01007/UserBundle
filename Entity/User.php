<?php

namespace DB\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Table(name="API_user")
 * @ORM\Entity(repositoryClass="DB\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_date", type="datetime", nullable=false)
     * @Expose
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="change", field={"email","first_name","last_name","password"})
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     * @Expose
     */
    private $updatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="text")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="text")
     */
    private $firstName;

    public function __construct() {
        parent::__construct();
        $this->addRole("ROLE_USER");
    }
    
    /**
     * Set created
     *
     * @param \DateTime $createdDate
     * @return User
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updatedDate
     * @return User
     */
    public function setUpdatedfate($updatedDate) {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdatedDate() {
        return $this->updatedDate;
    }
    
    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get email
     *
     * @return lastName 
     */
    public function getLastName() {
        return $this->lastName;
    }
    
    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get email
     *
     * @return firstName 
     */
    public function getFirstname() {
        return $this->firstName;
    }

}
