<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * synry63\BlogBundle\Entity\Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ThemeRepository")
 */
class Theme
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=64)
     */
    private $nom;
     
    /**
     * @var int max_prog
     *
     * @ORM\Column(name="max_prog", type="integer")
     */
    private $maxProg;
    
     /**
     * @var int ordre
     *
     * @ORM\Column(name="ordre",type="integer")
     */
    private $ordre;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    
    
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="synry63\BlogBundle\Entity\ThemeUtilisateur", mappedBy="theme")
     */
    private $users;


    public function getMaxProg(){
        return $this->maxProg;
    }
    
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
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
}