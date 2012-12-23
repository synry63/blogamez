<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="synry63\BlogBundle\Entity\ArticleUtilisateurRepository")
 */
class ArticleUtilisateur
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="synry63\BlogBundle\Entity\Article",inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="synry63\UserBundle\Entity\User",inversedBy="articles")
     * @ORM\JoinColumn(nullable=false) 
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateVue;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $rank;
    
    public function getArticle()
    {
        return $this->article;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function setArticle($article)
    {
        $this->article = $article;
    }
    public function setUser($user)
    {
        $this->user = $user;
    }
        public function getDateVue()
    {
        return $this->dateVue;
    }
    public function setDateVue($date)
    {
        $this->dateVue = $date;
    }
    public function getRank()
    {
        return $this->rank;
    }
    public function setRank($r)
    {
        $this->rank = $r;
    }
    
    
}