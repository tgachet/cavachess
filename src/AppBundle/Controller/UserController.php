<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/user/{id}")
     */
    public function displayInfo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
        
        return $this->render(
            'user/display.html.twig',
            [
                'user' => $user,               
            ]
        );
    }
    
}
