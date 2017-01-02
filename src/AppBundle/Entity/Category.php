<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Le nom de la rubrique est obligatoire")
     * @Assert\Length(max="20", maxMessage="Le nom de la rubrique ne doit pas dépasser {{ limit }} caractères") 
     */
    private $name;
    
     
    /**
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     * 
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
    
    public function getName() {
        return $this->name;
    }

    public function getPosts() {
        return $this->posts;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setPosts(ArrayCollection $posts) {
        $this->posts = $posts;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

