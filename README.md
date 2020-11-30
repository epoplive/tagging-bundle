tagging-bundle
==============
Since I've had to modify both the FPN\TagBundle and the Fogs\TaggingBundle
 to work with symfony 5, I'm going to just combine them into my own complete 
 bundle for easier updating and tracking.
 
 installation should be as follows:
 composer require "max-favilli/tagmanager":"dev-master as v3.0.1" "evilpope/tagging-bundle"
 
 add this line to your bundles.php:
 Evilpope\TaggingBundle\EvilpopeTaggingBundle::class => ['all' => true],
 
 will probably need this in config somewhere:
 evilpope_tagging:
     model:
         tag_class:     App\Entity\Tag
         tagging_class: App\Entity\Tagging


tagging-bundle
==============

Tag any entity in your Symfony2 project. This bundles takes care of the 
frontend using the jQuery Plugin from max-favilli/tagmanager and of the
backend using FabienPennequin/FPNTagBundle, which is a convenient 
wrapper around a Doctrine extension.

**Navigation**

1. [Installation](#installation)
2. [Making an entity taggable](#taggable-entity)
3. [Using Tags](#using-tags)

<a name="installation"></a>

## Installation

### Use Composer

You can either use composer to add the bundle :

``` sh
$ php composer.phar require max-favilli/tagmanager:dev-master
$ php composer.phar require evilpope/tagging-bundle:@dev
```

Or you can edit your `composer.json` where you have to add the following:

    "require": {
        "max-favilli/tagmanager": "dev-master",
        "evilpope/tagging-bundle":"@dev"
    }

### Setup the bundle

To start using the bundle, register it in your Kernel. This file is usually located at `app/AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Evilpope\TagBundle\FPNTagBundle(),
            new Evilpope\TaggingBundle\EvilpopeTaggingBundle(),
        );
    )

(Do you know how to get rid of the line for FPNTagBundle()? Please tell me. Or better: fork & fix. Thanks.)

Activate the bundles configuration by adding an imports statement in your config. This file is usually located at `app/config/config.yml`:

``` yaml
imports:
	# ...
    - { resource: "@EvilpopeTaggingBundle/Resources/config/config.yml" }
```

Add routes to this bundle. Only needed, if you plan to use typeahead. This file is usually located at `app/config/routing.yml`:

``` yaml
evilpope_tag:
    resource: "@EvilpopeTaggingBundle/Controller"
    type:     annotation
    prefix:   /
```

Dump all newly installed assets and update the database schema

``` sh
$ app/console assetic:dump
$ app/console doctrine:schema:update --force
```

Ensure that the bundle's CSS and JS files are loaded. Most likely you want to do that in your `app/Resources/views/base.html.twig`

``` twig
	<link rel="stylesheet" type="text/css" href="{{ asset('css/tagging.css') }}" />
	<script src="{{ asset('js/tagging-bundle.js') }}"></script>
```

Since the tagging.js relies on JQuery, the `<script>` tag must be somewhere after JQuery has been loaded.

### Setup an entity

In this example, we use the entity `Profile` - yours may of course have a different name.

``` php
use Evilpope\TaggingBundle\Interfaces\Taggable;
use Evilpope\TaggingBundle\Traits\TaggableTrait;
 
/**
 * Profile
 */
class Profile implements Taggable
{
	use TaggableTrait;
	
	// ...
}
```

Traits require PHP >5.4 - if you are not able to upgrade, you may also copy & paste the content of the TaggableTrait class into your entity instead of the `use TaggableTrait;`. However, baby seals die whenever you do that, so consider upgrading again.

Afterwards add a new input to the form builder of your entity:

``` php
class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ..
            ->add('tags', 'tags')
        ;
    }
```

Now you should be able to edit the entity and have tags available.

To access tags that are assigned, use the tags attribute of the entity. In a twig you could do this:

``` twig
	<ul>
	{% for key, value in profile.tags %}
	  <li>{{ value }}</li>
	{% endfor %} 
	</ul>
```

FPNTagBundle
============

This bundle adds tagging to your Symfony project, with the ability to associate
tags with any number of different entities. This bundle integrates the
[DoctrineExtensions-Taggable](https://github.com/FabienPennequin/DoctrineExtensions-Taggable)
library, which handles most of the hard work.

**Navigation**

1. [Installation](#installation)
2. [Making an entity taggable](#taggable-entity)
3. [Using Tags](#using-tags)

<a name="installation"></a>

## Installation

### Use Composer

You can use composer to add the bundle :

    ``` sh
    $ php composer.phar require fpn/tag-bundle
    ```

Or you can edit your composer.json, and add :

    "require": {
        "fpn/tag-bundle":"dev-master",
    }

### Register the bundle

To start using the bundle, register it in your Kernel. This file is usually
located at `app/AppKernel`:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new FPN\TagBundle\FPNTagBundle(),
        );
    )

### Create your `Tag` and `Tagging` entities

To use this bundle, you'll need to create two new entities: `Tag` and `Tagging`.
You place these in any bundle, but each should look like this:

```php
<?php

namespace Acme\TagBundle\Entity;

use FPN\TagBundle\Entity\Tag as BaseTag;

class Tag extends BaseTag
{
}
```

```php
<?php

namespace Acme\TagBundle\Entity;

use \FPN\TagBundle\Entity\Tagging as BaseTagging;

class Tagging extends BaseTagging
{
}
```

Next, you'll need to add a little bit of mapping information. One way
to do this is to create the following two XML files and place them in
the `Resources/config/doctrine` directory of your bundle:

*src/Acme/TagBundle/Resources/config/doctrine/Tag.orm.xml*:

```xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

    <entity name="Acme\TagBundle\Entity\Tag" table="acme_tag">

        <id name="id" column="id" type="integer">
            <generator strategy="AUTO" />
        </id>

        <one-to-many field="tagging" target-entity="Acme\TagBundle\Entity\Tagging" mapped-by="tag" fetch="EAGER" />

    </entity>

</doctrine-mapping>
```

*src/Acme/TagBundle/Resources/config/doctrine/Tagging.orm.xml*:

```xml
<?xml version="1.0" encoding="utf-8"?>
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                  http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

    <entity name="Acme\TagBundle\Entity\Tagging" table="acme_tagging">

        <id name="id" column="id" type="integer">
            <generator strategy="AUTO" />
        </id>

        <many-to-one field="tag" target-entity="Acme\TagBundle\Entity\Tag">
            <join-columns>
                <join-column name="tag_id" referenced-column-name="id" />
            </join-columns>
        </many-to-one>

        <unique-constraints>
            <unique-constraint columns="tag_id,resource_type,resource_id" name="tagging_idx" />
        </unique-constraints>

    </entity>

</doctrine-mapping>
```

You can also use Annotations :

*src/Acme/TagBundle/Entity/Tag.php*:

```php
namespace Acme\TagBundle\Entity;

use \FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\TagBundle\Entity\Tag
 *
 * @ORM\Table()
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
     * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;
}
```

*src/Acme/TagBundle/Entity/Tagging.php*:

```php
namespace Acme\TagBundle\Entity;

use \FPN\TagBundle\Entity\Tagging as BaseTagging;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Acme\TagBundle\Entity\Tagging
 *
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="tagging_idx", columns={"tag_id", "resource_type", "resource_id"})})
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
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     **/
    protected $tag;
}
```

<a name="taggable-entity"></a>

## Define classes on configuration

On your configuration you have to define tag and tagging classes.

Example on yaml:

```yaml

fpn_tag:
    model:
        tag_class:     Acme\TagBundle\Entity\Tag
        tagging_class: Acme\TagBundle\Entity\Tagging

```


## Making an Entity Taggable

Suppose we have a `Post` entity, and we want to make it "taggable". The setup
is simple: just add the `Taggable` interface and add the necessary 3 methods:

```php
<?php

namespace Acme\BlogBundle\Entity;

use DoctrineExtensions\Taggable\Taggable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_post")
 */
class Post implements Taggable
{
    private $tags;
    
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'acme_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }
}
```

That's it! As you'll see in the next section, the tag manager can now manage
the tags that are associated with your entity.

<a name="using-tags"></a>

## Using Tags

The bundle works by using a "tag manager", which is responsible for creating
tags and adding them to your entities. For some really good usage instructions,
see [Using TagManager](https://github.com/FabienPennequin/DoctrineExtensions-Taggable).

Basically, the idea is this. Instead of setting tags directly on your entity
(e.g. Post), you'll use the tag manager to set the tags for you. Let's see
how this looks from inside a controller. The tag manager is available as
the `fpn_tag.tag_manager` service:

    use Acme\BlogBundle\Entity\Post;

    public function createTagsAction()
    {
        // create your entity
        $post = new Post();
        $post->setTitle('foo');

        $tagManager = $this->get('fpn_tag.tag_manager');

        // ask the tag manager to create a Tag object
        $fooTag = $tagManager->loadOrCreateTag('foo');

        // assign the foo tag to the post
        $tagManager->addTag($fooTag, $post);

        $em = $this->getDoctrine()->getEntityManager();

        // persist and flush the new post
        $em->persist($post);
        $em->flush();

        // after flushing the post, tell the tag manager to actually save the tags
        $tagManager->saveTagging($post);

        // ...

        // Load tagging ...
        $tagManager->loadTagging($post);
    }
