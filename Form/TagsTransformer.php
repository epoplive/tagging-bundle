<?php

namespace Evilpope\TaggingBundle\Form;

use Evilpope\TaggingBundle\Entity\TagManager;

use Symfony\Component\Form\DataTransformerInterface;

class TagsTransformer implements DataTransformerInterface
{

    /**
     * @var TagManager
     */
    private $tagManager;

    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($tags)
    {
        if (!is_null($tags)) {
            return join(', ', $tags->toArray());
        }

        return '';
    }

    public function reverseTransform($tags)
    {
        return $this->tagManager->loadOrCreateTags(
                $this->tagManager->splitTagNames($tags)
        );
    }
}
