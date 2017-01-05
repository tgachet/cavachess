<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cet email n'est pas disponible")
 * @UniqueEntity(fields="username", message="Un utilisateur existe déja avec ce pseudo")
 */
class User implements UserInterface, Serializable
{
    /***** PROPERTIES *****/
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $firstname;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lastname;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=30, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max="30")
     */
    private $username;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;
    
        
    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email
     * 
     */
    private $email;
    
   /**
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    
    private $date;

        /**
     * @var string
     * @ORM\Column(type="string", length=20)
     * 
     */
    
    private $role = 'ROLE_USER';
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Image()
     */
    private $avatar;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    private $plainPassword;

    /**
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author") 
     */
    private $posts;

    /**
     * Many Users have Many Users.
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     */
    private $friendsWithMe;

    /**
     * Many Users have many Users.
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_friend_user", referencedColumnName="id")}
     *      )
     */
    private $myFriends;
    
    /**
     *
     * @var ArrayCollection 
     */
    private $allfriends;
    
    /**
     *
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="Ranking", mappedBy="user_id")
     */
    private $player;
    
    /**
     *
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="GamesFinished", mappedBy="idwinner")
     */
    private $gamewinner;
    
    /**
     *
     * @var ArrayCollection 
     * @ORM\OneToMany(targetEntity="GamesFinished", mappedBy="idlooser")
     */
    private $gamelooser;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
         $this->posts = new ArrayCollection();
         $this->myFriends = new ArrayCollection();
         $this->friendsWithMe = new ArrayCollection();
         $this->gamelooser = new ArrayCollection();
         $this->gamewinner = new ArrayCollection();
         $this->player = new ArrayCollection();
         $this->allfriends = new ArrayCollection(
                            array_merge($friendswithme->toArray(), $myfriends->toArray())
                            );
         $this->date = new \DateTime();
    }

    /*
     * Ne pas oublier de construire une fonction getAllFriends() qui permet de fusionner les deux ArrayCollections de myFriends et de friendsWithMe pour avoir l'ensemble de la liste d'amis
     */
    
    /***** GETTERS *****/
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function getRole() {
        return $this->role;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }
    
     public function getPosts() {
        return $this->posts;
    }   
    
    public function getFriendsWithMe() {
        return $this->friendsWithMe;
    }

    public function getMyFriends() {
        return $this->myFriends;
    }
    
    public function getAllFriends(){
         return $this->allfriends;
    }
    
    public function getPlayer() {
        return $this->player;
    }

    public function getGamewinner() {
        return $this->gamewinner;
    }

    public function getGamelooser() {
        return $this->gamelooser;
    }

        
    /***** SETTERS *****/
    
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

   public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }
    
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    
    /***** OTHERS *****/
    
    /*** Depuis l'utilisateur prendre le post et l'attribuer à cet utilisateur au lieu de depuis Post ***/   
    public function addPost(Post $post)
    {
        $this->posts[] = $post;
        $post->setAuthor($this);
        
        return $this;
    }
    
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }
    
    
    /***** IMPLEMENTS *****/
    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return [$this->role];
    }

    public function getSalt() {
        return null;
    }

    public function serialize() {
        return serialize([
            $this->id,
            $this->username,
            $this->lastname,
            $this->firstname,
            $this->email,
            $this->avatar,
            $this->password,
            $this->myFriends,
            $this->friendsWithMe,
        ]);
    }

    public function unserialize($serialized) {
        list(
            $this->id,
            $this->username,
            $this->lastname,
            $this->firstname,
            $this->email,
            $this->avatar,
            $this->password,
            $this->myFriends,
            $this->friendsWithMe,
        ) = unserialize($serialized);
    }
    
    /**
     * 
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
    
    
    /**
     * Add User Friend
     * @param User $user
     */
    public function addFriendWithMe(User $user)
    {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas 
        if (!$this->friendsWithMe->contains($user)) { 
            if (!$user->getMyFriends()->contains($this)) { 
                $user->addMyFriend($this);  // Lie l'utilisateur à la liste d'amis. 
            } 
            $this->friendsWithMe->add($user); 
        } 
    }
    
    public function setFriendsWithMe($friends) 
    { 
        if ($friends instanceof ArrayCollection || is_array($friends)) { 
            foreach ($friends as $friend) { 
                $this->addFriendWithMe($friend); 
            } 
        } elseif ($friends instanceof User) { 
            $this->addFriendWithMe($friends); 
        } else { 
            throw new Exception("$friends must be an instance of User or ArrayCollection"); 
        } 
    } 

    /**
     * Add User Friend
     * @param User $user
     */
    public function addMyFriend(User $user)
    {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas 
        if (!$this->myFriends->contains($user)) { 
            if (!$user->getFriendsWithMe()->contains($this)) { 
                $user->addFriendWithMe($this);  // Lie l'utilisateur à la liste d'amis. 
            } 
            $this->myFriends->add($user); 
        } 
    }
    
    public function setMyFriends($users) 
    { 
        if ($friends instanceof ArrayCollection || is_array($friends)) { 
            foreach ($friends as $friend) { 
                $this->addMyFriend($friend); 
            } 
        } elseif ($friends instanceof User) { 
            $this->addMyFriend($friends); 
        } else { 
            throw new Exception("$friends must be an instance of User or ArrayCollection"); 
        } 
    }
    
//    public function setAllFriends($friendswithme, $myfriends){
//        $this->allfriends = new ArrayCollection(
//                            array_merge($friendswithme->toArray(), $myfriends->toArray())
//                            ); 
//        return $this;
//    }
}