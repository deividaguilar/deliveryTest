<?php

namespace Delivery\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DeliveryTestBundle:Default:index.html.twig');
    }
}
