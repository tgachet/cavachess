<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactController extends Controller
{
    /**
     * 
     * @param Request $request
     * @Route("/contact")
     */
    public function indexAction(Request $request)
    {
        $formBuilder = $this->createFormBuilder();
        
        $formBuilder 
            ->add(
                    'name',
                    TextType::class,
                    [
                        'label' => 'Nom',
                        'constraints' => new NotBlank(),
                    ]
            )
            ->add(
                    'email',
                    EmailType::class,
                    [
                        'label' => 'Email',
                        'constraints' => 
                                        [
                                            new NotBlank(),
                                            new Email(),
                                        ]
                    ]    
            )
            ->add(
                    'subject',
                    TextType::class,
                    [
                        'label' => 'Sujet',
                        'constraints' => new NotBlank(),
                    ]
            )
            ->add(
                    'body',
                    TextareaType::class,
                    [
                        'label' => 'Votre message',
                        'constraints' => new NotBlank(),
                    ]
            );
        
        $form = $formBuilder->getForm();
       
        // Préremplissage des champs
        if ($this->getUser())
        {
            $form ->get('name')->setData($this->getUser()->getFullName());
            $form ->get('email')->setData($this->getUser()->getEmail());
        }
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $data =$form->getData();
                
                /** @var Swift_Mailer */
                // afin d'envoyer un mail en HTML de manière facile
                $mailer = $this->get('mailer');
                $mail = $mailer->createMessage();
                $mailContent = "<h3>Nouveau message de ${data['name']} (${data['email']})"
                                .'<p><b>'.$data['subject'].'</b></p>'
                                .'<p>'.nl2br($data['body']).'</p>'
                ;
                $mail
                    ->setSubject('Nouveau message pour Cavachess')
                    ->setFrom($this->getParameter('contact_email'))
                    ->setTo($this->getParameter('contact_email'))
                    ->setBody($mailContent)
                    ->setReplyTo($data['email'])
                ;
                
                $mailer->send($mail);
                $this->addFlash('success', "Votre message vient d'être envoyé !");
            } else
            {
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }
        }    
            
        
        
        return $this->render(
                'contact/index.html.twig',
                [
                    'form' => $form->createView(),
                ]
         );
    }
}
