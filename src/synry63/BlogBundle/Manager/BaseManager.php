<?php

namespace synry63\BlogBundle\Manager;

abstract class BaseManager
{
    public function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
    public function flush()
    {
        $this->em->flush();
    }
}
?>
