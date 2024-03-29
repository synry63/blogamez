<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    public function getArticlesNoSee($userID,$lang){
     
        $dql = "SELECT a FROM synry63BlogBundle:Article a WHERE a.id NOT IN";
        $dql .= " (SELECT aa.id FROM synry63BlogBundle:ArticleUtilisateur au JOIN au.article aa ";
        $dql .= " JOIN au.user u WHERE u.id=:id) AND a.validate=1 AND a.lang=:lang";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('id',$userID);
        $query->setParameter('lang',$lang);
        
        return  $query->getResult();
    }
    public function test(){
        $dql = "SELECT a FROM synry63BlogBundle:Article a JOIN a.users u WHERE u.user=1 ORDER BY u.dateVue DESC";
        $query = $this->_em->createQuery($dql);
         return  $query->getResult();
        
    }
        public function getArticlesNoSeeFiltre($userID,$lang,$themeId){
     
        $dql = "SELECT a FROM synry63BlogBundle:Article a WHERE a.id NOT IN";
        $dql .= " (SELECT aa.id FROM synry63BlogBundle:ArticleUtilisateur au JOIN au.article aa ";
        $dql .= " JOIN au.user u WHERE u.id=:id) AND a.validate=1 AND a.lang=:lang AND a.theme=:theme";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('id',$userID);
        $query->setParameter('lang',$lang);
        $query->setParameter('theme',$themeId);
        
        return  $query->getResult();
    }
    public function valideArticle($id){
        $article = $this->find($id) ;
        $article->setValidate(1);
        $em = $this->getEntityManager();
        $em->flush();
    }

    public function removeArticle($id){
        $article = $this->find($id);
        $img =  $article->getImage();
        $result = $img->removeFile();
        $em = $this->getEntityManager();
        $em->remove($article);
        $em->flush();
    }

     
    
}