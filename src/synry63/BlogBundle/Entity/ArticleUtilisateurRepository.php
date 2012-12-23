<?php

namespace synry63\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleUtilisateurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleUtilisateurRepository extends EntityRepository
{
   public function getAverage($id){
        $dql = "SELECT AVG (au.rank) FROM synry63BlogBundle:ArticleUtilisateur au WHERE au.article=:id";
        $query = $this->_em->createQuery($dql);
        $query->setParameter('id',$id);
        $r =  $query->getResult();
        $rr = $r[0][1];
        return $rr; 
    }
    
}