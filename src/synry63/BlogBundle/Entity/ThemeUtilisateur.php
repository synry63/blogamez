<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * synry63\BlogBundle\Entity\ThemeUtilisateur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ThemeUtilisateurRepository")
 */
class ThemeUtilisateur
{


     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="synry63\BlogBundle\Entity\Theme",inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="synry63\UserBundle\Entity\User",inversedBy="themes")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $user;

    /**
     * @var integer $etat_prog
     *
     * @ORM\Column(name="etat_prog", type="integer")
     */
    private $etatProg;


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
     * Set theme
     *
     * @param object $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Get theme
     *
     * @return object 
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set user
     *
     * @param object $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return object 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set etat_prog
     *
     * @param integer $etatProg
     */
    public function setEtatProg($etatProg)
    {
        $this->etatProg = $etatProg;
    }

    /**
     * Get etat_prog
     *
     * @return integer 
     */
    public function getEtatProg()
    {
        return $this->etatProg;
    }
}