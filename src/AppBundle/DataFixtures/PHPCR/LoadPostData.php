<?php

namespace AppBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use AppBundle\Document\Post;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $dm)
    {
        if (!$dm instanceof DocumentManager) {
            $class = get_class($dm);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $parent = $dm->find(null, '/cms/posts');

        foreach (array('Pierwszy', 'Drugi', 'Trzeci', 'Czwarty') as $title) {
            $post = new Post();
            $post->setTitle(sprintf('Mój %s Post', $title));
            $post->setParentDocument($parent);
            $post->setContent(<<<HERE
To jest zawartość ...
HERE
            );

            $dm->persist($post);
        }

        $dm->flush();
    }
}
