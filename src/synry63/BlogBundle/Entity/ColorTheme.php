<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * synry63\BlogBundle\Entity\ColorTheme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ColorThemeRepository")
 */
class ColorTheme
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
     * @var string $bulleTip
     *
     * @ORM\Column(name="bulleTip", type="string", length=64)
     */
    private $bulleTip;
    
        /**
     * @var string $boutonStart
     *
     * @ORM\Column(name="boutonStart", type="string", length=64)
     */
    private $boutonStart;

    /**
     * @var string $backgroundColor
     *
     * @ORM\Column(name="backgroundColor", type="string", length=64)
     */
    private $backgroundColor;
    

    /**
    * @ORM\OneToMany(targetEntity="synry63\BlogBundle\Entity\Article", mappedBy="colorTheme")
    */
    private $articles;
    
    public function __construct()
    {
     /*   $this->id = 3;
        $this->backgroundColor = "#424242";
        $this->bulleTip = "ui-tooltip-dark";
        $this->boutonStart = "buttonGameDefault";
        */
    }
    
    public function getArticles()
    {
        return $this->articles;
    }
    
    public function getBoutonStart()
    {
        return $this->boutonStart;
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
     * Set bulleTip
     *
     * @param string $bulleTip
     */
    public function setBulleTip($bulleTip)
    {
        $this->bulleTip = $bulleTip;
    }

    /**
     * Get bulleTip
     *
     * @return string 
     */
    public function getBulleTip()
    {
        return $this->bulleTip;
    }

    /**
     * Set backgroundColor
     *
     * @param string $backgroundColor
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * Get backgroundColor
     *
     * @return string 
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }
}