<?php

namespace Evilpope\TaggingBundle\Service;

use DoctrineExtensions\Taggable\Taggable;
use Evilpope\TaggingBundle\Entity\TagManager as BaseTagManager;
use Doctrine\ORM\Query;

class TagManager extends BaseTagManager
{
    protected $dirtyResources = array();

    public function markDirty(Taggable $resource)
    {
        if (!in_array($resource, $this->dirtyResources)) {
            $this->dirtyResources[] = $resource;
        }
    }

    public function getDirtyRessources()
    {
        $dirtyResources = $this->dirtyResources;
        $this->dirtyResources = array();
        return $dirtyResources;
    }

    /**
     * Search for tags
     *
     * @param string  $search
     */
    public function findTags($search)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('t.name')
            ->from($this->tagClass, 't')
            ->where('t.slug LIKE :search')
            ->setParameter('search', strtolower('%' . $search . '%'))
            ->setMaxResults(5)
            ->orderBy('t.name')
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR)
        ;
    }
    /**
     * Preload tags for a set of objects.
     * It might be usefull in case you want to
     * display a long list of taggable objects with their associated tags: it
     * avoids to load tags per object, and gets all tags in a few requests.
     *
     * @param Iterable &$entities
     */
    public function preloadTags(Iterable &$entities)
    {
        $searched = array();
        foreach ($entities as $entity) {
            if (!$entity instanceof \Evilpope\TaggingBundle\Interfaces\Taggable) {
                throw new \InvalidArgumentException(
                    'Entities passed to TagManager::preloadTags() must implement DoctrineExtensions\Taggable\Taggable.'
                );
            }
            $taggable_type = $entity->getTaggableType();
            if (!isset($searched[$taggable_type])) {
                $searched[$taggable_type] = array();
            }
            $searched[$taggable_type][$entity->getTaggableId()] = $entity;
            $entity->clearTags();
        }

        $qb = $this->em->createQueryBuilder()
            ->select('tagging, tag')
            ->from($this->taggingClass, 'tagging')
            ->leftJoin('tagging.tag', 'tag')
            ->orderBy('tagging.resourceId');
        foreach($searched as $taggable_type => $instances)
        {
            $qb_clone = clone $qb;
            $taggings = $qb_clone
                ->where('tagging.resourceType = :type')
                ->andWhere(
                    $qb_clone->expr()->in('tagging.resourceId', array_keys($instances))
                )->setParameter('type', $taggable_type)
                ->getQuery()->getResult();
            foreach($taggings as $tagging)
            {
                $entity = $instances[$tagging->getResourceId()];
                $entity->addTag($tagging->getTag());
            }
        }
    }
}
