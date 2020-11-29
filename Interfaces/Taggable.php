<?php

/*
 * This file is part of the Doctrine Extensions Taggable package.
 * (c) 2011 Fabien Pennequin <fabien@pennequin.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evilpope\TaggingBundle\Interfaces;

use DoctrineExtensions\Taggable\Taggable as BaseTaggable;
use Evilpope\TaggingBundle\Service\TagManager;

interface Taggable extends BaseTaggable
{

    /**
     * Set an array of tags for this Taggable entity
     *
     * @var array
     */
    public function setTags(array $tags);

    public function setTagManager(TagManager $tagManager);
}
