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
        
        /* USER INFOS FROM USER */
        $user = $em->find('AppBundle:User', $id);
        $gameswon = count($user->getGamewinner());
        $gameslost = count($user->getGamelooser());
        $gamesplayed = $gameswon + $gameslost;
        
        /* USER POSTS INFO FROM POST */
        $posts = $em->getRepository('AppBundle:Post')->findByAuthor($id);
        

        /* TIME PLAYED FOR GAMES WON AND LOST */
        $gamewontimelength = $em->getRepository('AppBundle:GamesFinished')->findTimeByGamesWon($id);
        $gamelosttimelength = $em->getRepository('AppBundle:GamesFinished')->findTimeByGamesLost($id);
        
        $times = [];
        foreach ($gamewontimelength as $time){
            $times[] = $time->getGamelength()->format('H:i:s');
        }
        foreach ($gamelosttimelength as $time) {
            $times[] = $time->getGamelength()->format('H:i:s');
        }
        
        $seconds = 0;
        foreach ($times as $value) {
           list($hour,$minute,$second) = explode(':', $value);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        /* Time played */
        $totaltime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        
        return $this->render(
            'user/profile.html.twig',
            [
                'user' => $user,
                'id' => $id,
                'posts' =>$posts,
                'games' => array('played' => $gamesplayed, 'won' => $gameswon, 'lost' => $gameslost, 'timeplayed' => $totaltime),
            ]
        );
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
////        return array('cons' => $cons);
//    }
    
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
