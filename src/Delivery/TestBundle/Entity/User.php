<?php

namespace Delivery\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $numberPhone;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numberPhone
     *
     * @param string $numberPhone
     * @return User
     */
    public function setNumberPhone($numberPhone)
    {
        $this->numberPhone = $numberPhone;

        return $this;
    }

    /**
     * Get numberPhone
     *
     * @return string 
     */
    public function getNumberPhone()
    {
        return $this->numberPhone;
    }
}
