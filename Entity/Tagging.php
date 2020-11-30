<?php

namespace Evilpope\TaggingBundle\Entity;

use DoctrineExtensions\Taggable\Entity\Tagging as BaseTagging;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(name="tagging",
 * 		uniqueConstraints={@UniqueConstraint(name="tagging_idx", columns={"tag_id", "resource_type", "resource_id"})}
 * )
 * @ORM\Entity
 */
class Tagging extends BaseTagging
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
     * @ORM\ManyToOne(targetEntity="Evilpope\TaggingBundle\Entity\Tag", inversedBy="tagging"))
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     **/
    protected $tag;

    /**
     * @var string $resourceType
     *
     * @ORM\Column(name="resource_type", type="string")
     */
    protected $resourceType;
    /**
     * @var id $resourceId
     *
     * @ORM\Column(name="resource_id", type="integer")
     */
    protected $resourceId;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
}

