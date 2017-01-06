<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('AppBundle:Post')->findLatest(3);
        $cons = $em->getRepository('AppBundle:User')->getActive();
        
        return $this->render(
                'default/index.html.twig', 
                [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'posts' => $posts,
                    'cons' => $cons,
        ]);
    }
    
//    /**
//     * @param Request $request
//     * 
//     */
//    public function whoIsOnlineAction()
//    {
//        $cons = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->getActive();
// 
//        return $this->render(
//            'default/index.html.twig',
//            [
//                'cons' => $cons,
//            ]
//        );
//        return array('cons' => $cons);
//    }
}
