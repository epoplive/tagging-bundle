<?php

namespace Evilpope\TaggingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/tags")
 */
class DefaultController extends AbstractController
{

    /**
     * Search for tags
     *
     * @Route("/{query}", name="tags_search", defaults={"query" = ""})
     * @Method("GET")
     */

    public function searchAction($query, \Evilpope\TaggingBundle\Service\TagManager $tagManager)
    {
        $tags = $tagManager->findTags($query);
        $tags = array_map(function ($value) {
            return $value['name'];
        }, $tags);
        return new JsonResponse($tags);
    }

}
