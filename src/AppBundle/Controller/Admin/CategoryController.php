<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @Route ("/category")
 */
class CategoryController extends Controller
{
     
    /**
     * 
     * @param int $id
     * @Route("/edit/{id}", defaults={"id":null})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        // Envoi des données de catégories à la vue
        $categories = $em->getRepository('AppBundle:Category')->findAll();
         
         
        // Création ou modification de catégories
        if(is_null($id)) // création
        {
            $new= true;
            $category = new Category();
        }
        else // modification
        {
            $new = false;
            $category = $em->find('AppBundle:Category', $id);
             
            if(is_null($category))
            {
                return $this->redirectToRoute('app_admin_category_edit');
            }
        }
        $form = $this->createForm(CategoryType::class, $category);
         
        $form->handleRequest($request);
         
        if($form->isSubmitted()) // Si le formulaire a été soumis
        {
            if($form->isValid()) // S'il n'y a pas eu d'erreur de validation du formulaire
            {
                $em->persist($category);
                $em->flush();                 

                $msg = ($new)
                        ? 'La catégorie a bien été créée'
                        : 'La catégorie a bien été modifiée'
                      ;
                $this->addFlash('success', $msg);
                return $this->redirectToRoute('app_admin_category_edit');
            }
            else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }
         
         return $this->render('admin/category/edit.html.twig',
        [
           'new' => $new,
           'form' => $form->createView(),
           'categories' => $categories,
        ]);       
    }
     
    /**
     * @param int $id
     * @Route("/delete/{id}")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->find('AppBundle:Category', $id);
         
        if(is_null($category))
        {
            // On redirige vers la route de la liste s'il n'y a pas de categories
            return $this->redirectToRoute('app_admin_category_edit');
        } 
        $em->remove($category);
        $em->flush();
         
        $this->addFlash('success', 'La catégorie a bien été supprimée');
         
        return $this->redirectToRoute('app_admin_category_edit');
    }
}
