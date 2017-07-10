<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
     /**
     * Load the site definition and redirect to the default page.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        $dm = $this->get('doctrine_phpcr')->getManager();
       // $site = $dm->find('AppBundle\Document\Site', '/cms');
    //    $homepage = $site->getHomepage();

       /* if (!$homepage) {
            throw $this->createNotFoundException('No homepage configured');
        }*/

        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Template()
     */
    public function pageAction($contentDocument)
    {
        $dm = $this->get('doctrine_phpcr')->getManager();
        $posts = $dm->getRepository('AppBundle:Post')->findAll();

        return array(
            'page'  => $contentDocument,
            'posts' => $posts,
        );
    }
    
     /**
     * @Route(
     *   name="make_homepage",
     *   pattern="/admin/make_homepage/{id}",
     *   requirements={"id": ".+"}
     * )
     * @Method({"GET"})
     */
    public function makeHomepageAction($id = 1)
    {
        $dm = $this->get('doctrine_phpcr')->getManager();

        $site = $dm->find(null, '/cms');
        if (!$site) {
            throw $this->createNotFoundException('Could not find /cms document!');
        }

        $page = $dm->find(null, $id);

        $site->setHomepage($page);
        $dm->persist($page);
        $dm->flush();

        return $this->redirect($this->generateUrl('admin_app_page_edit', array(
            'id' => $page->getId()
        )));
    }
}
