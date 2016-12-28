<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cet email n'est pas disponible")
 * @UniqueEntity(fields="username", message="Un utilisateur existe déja avec ce pseudo")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @var string
     *  @ORM\Column(type="string", length=20)
     * 
     */
    
    private $role = 'ROLE_USER';
    
    /**
     * @var string
     *  @ORM\Column(type="string", nullable=true)
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
    
    public function __construct()
    {
         $this->posts = new ArrayCollection();
    }

    
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
    
    public function getRole() {
        return $this->role;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

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
    
    public function getPosts() {
        return $this->posts;
    }

    public function setPosts(ArrayCollection $posts) {
        $this->posts = $posts;
        return $this;
    }
    
    // Optionnels
    public function addArticle(Post $post)
    {
        $this->posts[] = $post;
        $article->setAuthor($this);
        
        return $this;
    }
    
    public function removeArticle(Article $post)
    {
        $this->posts->removeElement($post);
    }
    
    /**
     * 
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}

