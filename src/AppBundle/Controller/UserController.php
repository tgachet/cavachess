<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * 
     * @param Request $request
     * @Route("/list")
     */
    public function displayAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        
        return $this->render(
            'user/list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/{id}")
     */
    public function displayInfoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
        
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
            ]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/add/{id}")
     */
    public function addFriendAction(Request $request, $id)
    {
        $friend = [$id];
        $this->addFriend($friend);
//        return $this->render(
//            'user/profile.html.twig',
//            [
//                'user' => $id,
//            ]
//        );
    }
}
