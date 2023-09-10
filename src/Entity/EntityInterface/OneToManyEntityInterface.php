<?php

namespace App\Entity\EntityInterface;

interface OneToManyEntityInterface
{
    public function addRelation(ManyToOneEntityInterface $entity);
    public function removeRelation(ManyToOneEntityInterface $entity);
    public function getClassName(): string;

}