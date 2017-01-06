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
    public function displayInfo($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->find('AppBundle:User', $id);
        $posts = $em->getRepository('AppBundle:Post')->findByAuthor($id);

        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
                'id' => $id,
                'posts' =>$posts,
            ]
        );
    }
    
    /**
     * 
     * @param Request $request
     * @param int $id
     * @Route("/add/{id}")
     */
//    public function addFriendAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $friend = $em->getRepository('AppBundle:User')->find($id);
//        if (!$friend) {
//            throw $this->createNotFoundException(
//                'No user found for id '.$id
//            );
//        }
//        
//        $user = $this->getUser();
//        $friend->setFriendsWithMe($user);
//        $em->persist($friend);
//        $em->flush();
//            
//        return $this->render(
//            'user/profile.html.twig',
//            [
//                'user' => $user,
//            ]
//        );
//        $user = $this->getDoctrine()
//                     ->getRepository('AppBundle:User')
//                     ->findOneById($id);
//        
//        $friends = $user->getMyFriends();
//        $names = array();
//        foreach($friends as $friend)
//        {
//            $names[] = $friend->getName();
//        }
    
}
