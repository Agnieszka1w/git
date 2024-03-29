<?php

// src/AppBundle/Initializer/SiteInitializer.php
namespace AppBundle\Initializer;

use Doctrine\Bundle\PHPCRBundle\Initializer\InitializerInterface;
use PHPCR\Util\NodeHelper;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use AppBundle\Document\Site;

class SiteInitializer implements InitializerInterface
{
    private $basePath;

    public function __construct($basePath = '/cms')
    {
        $this->basePath = $basePath;
    }

    public function init(ManagerRegistry $registry)
    {
        $dm = $registry->getManagerForClass('AppBundle\Document\Site');
        if ($dm->find(null, $this->basePath)) {
            return;
        }

        $site = new Site();
        $site->setId($this->basePath);
        $dm->persist($site);
        $dm->flush();

        $session = $registry->getConnection();

        // create the 'cms', 'pages', and 'posts' nodes
        NodeHelper::createPath($session, $this->basePath . '/pages');
        NodeHelper::createPath($session, $this->basePath . '/posts');
        NodeHelper::createPath($session, $this->basePath . '/routes');

        $session->save();
    }

    public function getName()
    {
        return 'My site initializer';
    }
}