<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     *
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank() 
     */
    private $title;
    
    /**
     *
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank() 
     */
    private $content;
    
    /**
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")  
     */
    private $date;
    
    /**
     * 
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post") 
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false, onDelete="CASCADE") 
     */
    private $post;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE") 
     */
    private $user;

    /****** CONSTRUCTOR *****/
    public function __construct()
    {
        $this->date = new \DateTime();
    }
    
    /****** GETTERS *****/
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDate() {
        return $this->date;
    }

    public function getPost() {
        return $this->post;
    }

    public function getUser() {
        return $this->user;
    }
    
    
    /****** SETTERS *****/
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
        return $this;
    }

    public function setPost(Post $post) {
        $this->post = $post;
        return $this;
    }

    public function setUser(User $user) {
        $this->user = $user;
        return $this;
    }


}

