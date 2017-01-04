<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
    //// WIP /////   
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/add/{id}")
     */
    public function addFriendAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
                     ->getRepository('AppBundle:User')
                     ->findOneById($id);
        $friends = $user->getMyFriends();
        $names = array();
        foreach($friends as $friend) 
        {
            $names[] = $friend->getName();
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
        
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
