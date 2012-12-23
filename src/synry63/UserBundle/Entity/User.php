<?php
// src/Sdz/UserBundle/Entity/User.php

namespace synry63\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\OneToMany(targetEntity="synry63\BlogBundle\Entity\ArticleUtilisateur", mappedBy="user")
     */
    private $articles;
    
         /**
     * @ORM\OneToMany(targetEntity="synry63\BlogBundle\Entity\ThemeUtilisateur", mappedBy="user")
     */
    private $themes;

     /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    public function getThemes()
    {
        return $this->themes;
    }
}
?>