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
     * @var ArrayCollection Post $posts
     * 
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

//    public function getPosts() {
//        return $this->posts;
//    }

    /***** SETTERS *****/
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
//    public function setPosts(ArrayCollection $posts) {
//        $this->posts = $posts;
//        return $this;
//    }

    /***** OTHERS *****/
    
    /**
     * @return string
     * Retourne le nom en chaine de caractère
     */
    public function __toString()
    {
        return $this->name;
    }
    
     /**
     * Add Post
     *
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->posts->contains($post)) {
            if (!$post->getProduits()->contains($this)) {
                $post->addProduit($this);  // Lie le Post a la categorie.
            }
            $this->posts->add($post);
        }
    }
 
    public function setPosts($items)
    {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addPost($item);
            }
        } elseif ($items instanceof Post) {
            $this->addPost($items);
        } else {
            throw new Exception("$items must be an instance of Client or ArrayCollection");
        }
    }
 
    /**
     * Get ArrayCollection
     *
     * @return ArrayCollection $posts
     */
    public function getPosts()
    {
        return $this->posts;
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

