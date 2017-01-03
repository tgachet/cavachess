<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /***** PROPERTIES *****/
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
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le titre est obligatoire")
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
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */   
    private $description;
    
    /**
     *
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;
    
    /**
     *
     * @var Category
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="posts_categories",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      ))
     * @Assert\NotBlank()
     */
    private $categories;
    
    /***** CONSTRUCT *****/
    public function __construct() {
        $this->categories = new ArrayCollection();
    }

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
    
    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAuthor() {
        return $this->author;
    }

    

    public function getCategories() {
        return $this->categories;
    }

    /***** SETTERS *****/
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }
    public function setCategories(Doctrine\Common\Collections\ArrayCollection $categories) {
        $this->categories = $categories;
        return $this;
    }
//    public function addCategory(Category $category)
//    {
//        $this->categories[] = $category;
//        $category->addPost($this);
//        return $this;
//    }
//    
//    public function removeCategory(Category $category)
//    {
//        $this->categories->removeElement($category);
//    }    
//    
     public function __toString()
    {
        return $this->name;
    }
}
