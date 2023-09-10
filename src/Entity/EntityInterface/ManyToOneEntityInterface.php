<?php

namespace App\Entity\EntityInterface;

interface ManyToOneEntityInterface
{
    public function setRelation(OneToManyEntityInterface $entity);

}