<?php

namespace Delivery\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Grupo
 */
class Grupo extends BaseGroup
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $groups;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
