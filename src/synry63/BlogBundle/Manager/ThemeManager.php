<?php
namespace synry63\BlogBundle\Manager;

use Doctrine\ORM\EntityManager;
use synry63\BlogBundle\Manager\BaseManager;
use synry63\BlogBundle\Entity\Theme;

class ThemeManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRepository()
    {
        return $this->em->getRepository('synry63BlogBundle:Theme');
    }
}
?>
