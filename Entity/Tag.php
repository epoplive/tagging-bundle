<?php

namespace Evilpope\TaggingBundle\Entity;

use DoctrineExtensions\Taggable\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag extends BaseTag
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Evilpope\TaggingBundle\Entity\Tagging", mappedBy="tag")
     **/
    protected $tagging;

    /**
     * @ORM\Column(name="name", type="string")
     */
    protected $name;
    /**
     * @ORM\Column(name="slug", type="string")
     */
    protected $slug;
    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Returns tag slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets tag slug
     *
     * @return string
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
