<?php
namespace synry63\BlogBundle\Manager;

use Doctrine\ORM\EntityManager;
use synry63\BlogBundle\Manager\BaseManager;
use synry63\BlogBundle\Entity\Article;

class ArticleManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
    * Save article entity
    *
    * @param Article $a 
    */
    public function saveArticle(Article $a)
    {
        $this->persistAndFlush($a);
    }
    public function getRepository()
    {
        return $this->em->getRepository('synry63BlogBundle:Article');
    }
}
?>
