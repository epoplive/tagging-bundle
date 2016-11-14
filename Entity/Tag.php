<?php

namespace Fogs\TaggingBundle\Entity;

use \FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="tags")
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
     * @var string
	 * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @var string
	 * @ORM\Column(name="slug", type="string", length=50)
     */
    protected $slug;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Fogs\TaggingBundle\Entity\Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;
}
