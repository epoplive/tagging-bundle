<?php

namespace Evilpope\TaggingBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Evilpope\TaggingBundle\Interfaces\Taggable;
use Evilpope\TaggingBundle\Service\TagManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TaggableSubscriber implements EventSubscriber
{
    /**
     * @var Container
     */
    protected $container;
    protected $tagManagerService;

    public function __construct(Container $container,  \Evilpope\TaggingBundle\Service\TagManager $tagManager)
    {
        $this->container = $container;
//        $this->tagManagerService = $tagManagerService;
        $this->tagManager = $tagManager;
    }

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @return TagManager
     */
    protected function getTagManager()
    {
        if (!$this->tagManager) {
            $this->tagManager = $this->container->get($this->tagManagerService);
        }
        return $this->tagManager;
    }

    public function getSubscribedEvents()
    {
        return array(
                Events::onFlush,
                Events::postLoad,
                Events::postPersist,
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        foreach ($this->getTagManager()->getDirtyRessources() as $entity) {
            if ($entity instanceof Taggable) {
                $this->getTagManager()->saveTagging($entity);
            }
        }
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Taggable) {
            $entity->setTagManager($this->getTagManager());
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Taggable) {
            $this->getTagManager()->saveTagging($entity);
        }
    }
}
