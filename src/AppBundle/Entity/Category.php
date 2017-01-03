<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le nom de la rubrique est obligatoire")
     * @Assert\Length(max="20", maxMessage="Le nom de la rubrique ne doit pas dépasser {{ limit }} caractères") 
     */
    private $name;   
     
    /**
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories", cascade={"persist", "merge"})
     * 
     */
    private $posts;
    
    /***** CONSTRUCT *****/
    public function __construct()
    {
         $this->posts = new ArrayCollection();
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
    
    public function getName() {
        return $this->name;
    }

    public function getPosts() {
        return $this->posts;
    }

    /***** SETTERS *****/
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    public function setPosts(ArrayCollection $posts) {
        $this->posts = $posts;
        return $this;
    }

    /***** OTHERS *****/
    
    /**
     * @return string
     * Retourne le nom en chaine de caractère
     */
    public function __toString()
    {
        return $this->name;
    }
    
     /*** Depuis la catégorie prendre le post et l'attribuer à cette catégorie au lieu de depuis Post ***/
//    public function addPost(Post $post)
//    {
//        $this->posts[] = $post;
//        $post->addCategories($this);
//        return $this;
//    }
//    
//    public function removePost(Post $post)
//    {
//        $this->posts->removeElement($post);
//    }    
//    
}

