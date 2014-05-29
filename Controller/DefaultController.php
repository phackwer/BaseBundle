<?php

namespace SanSIS\Core\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SanSISCoreBaseBundle:Default:index.html.twig', array('name' => $name));
    }
}
