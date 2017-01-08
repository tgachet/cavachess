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
        
        // Requête des trois derniers posts publiés 
        $posts = $em->getRepository('AppBundle:Post')->findLatest(3);
        
        // Requête des utilisateurs connectés sur le site
        $cons = $em->getRepository('AppBundle:User')->getActive();
        
        // Requête du post le plus commenté
        $mostcommented = $em->createQuery("SELECT c, max(c.post) FROM AppBundle\Entity\Comment c")->getResult();
        $top_article = $mostcommented[0][0]->getPost();
        
        // Requête des trois derniers commentaires du post le plus commeenté
        $comments = $em->getRepository('AppBundle:Comment')->findLatestByPost($top_article, 3);
        
        return $this->render(
                'default/index.html.twig', 
                [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'posts' => $posts,
                    'cons' => $cons,
                    'mostcommented' => $mostcommented,
                    'top_article' =>$top_article,
                    'comments' => $comments,
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
