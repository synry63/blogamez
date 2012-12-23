<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * synry63\BlogBundle\Entity\Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ArticleRepository")
 */
class Article
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
     * @var string $titre
     * @Assert\MinLength(5)
     * @Assert\MaxLength(20)
     * @ORM\Column(name="titre", type="string", length=64)
     */
    private $titre;
    /**
     * @var text $texte
     * @Assert\MinLength(50)
     * @Assert\MaxLength(640)
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;
    
    /**
     * @var string $lang
     *
     * @ORM\Column(name="lang", type="string", length=2)
     */
    private $lang;
    
     /**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string", length=255)
     * @Assert\Url()
     */
    private $link;

    
     /**
     * @ORM\OneToMany(targetEntity="synry63\BlogBundle\Entity\ArticleUtilisateur", mappedBy="article")
     */
    private $users;
        
    
     /**
     * @ORM\ManyToOne(targetEntity="synry63\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;
    
     /**
     * @ORM\ManyToOne(targetEntity="synry63\BlogBundle\Entity\Theme")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;
    
    /**
     * @ORM\ManyToOne(targetEntity="synry63\BlogBundle\Entity\Categorie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorie;
    
     /**
     * @ORM\ManyToOne(targetEntity="synry63\BlogBundle\Entity\ColorTheme")
     * @ORM\JoinColumn(nullable=false)
     */
    private $colorTheme;
    
     /**
     * @ORM\OneToOne(targetEntity="synry63\BlogBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\File(maxSize="500000")
     */
    private $image;
    
    /**
     * @var bool $validate
     *
     * @ORM\Column(name="validate", type="boolean")
     */
    private $validate;
     /**
     * @var bool $demo
     * @ORM\Column(name="demo", type="boolean",nullable=true) 
     */
    private $demo;

    
    
    
    public function __construct()
    {
        $this->validate=0;
    }

    
    
    public function getLink(){
        return $this->link;
    }
    public function setLink($link){
        return $this->link=$link; 
    }
    
    public function getValidate(){
        return $this->validate;
    }
    public function setValidate($etat){
        return $this->validate=$etat; 
    }
    
        public function getLang(){
        return $this->lang;
    }
    public function setlang($l){
        return $this->lang=$l; 
    }
    
     /**
     * Get utilisateurs
     *
     * @return utilisateurs 
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
         /**
     * Get categorie
     *
     * @return utilisateurs 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    public function setCategorie($categorie)
    {
        return $this->categorie = $categorie;
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
     * Set texte
     *
     * @param text $texte
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    }

    /**
     * Get texte
     *
     * @return text 
     */
    public function getTexte()
    {
        return $this->texte;
    }
             /**
     * set theme
     *
     * @return utilisateurs 
     */
    public function getTheme()
    {
        return $this->theme;
    }
        /**
     * Get color
     *
     * @return text 
     */
    public function getColorTheme()
    {
        return $this->colorTheme;
    }
    
    public function setColorTheme($colorTheme)
    {
        $this->colorTheme = $colorTheme;
    }
     /**
     * Get titre
     *
     * @return text 
     */
    public function getTitre()
    {
        return $this->titre;
    }
    
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    public function getImage()
    {
        return $this->image;
    }
    
    public function setImage($image)
    {
        $this->image = $image;
    }
    public function getDemo(){
        return $this->demo;
    }
    public function setDemo($d){
        $this->demo = $d;
    }

}