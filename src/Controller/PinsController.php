<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app.home", methods="GET")
     */
    public function home(PinRepository $pinRepository): Response
    {
        $pins =  $pinRepository->findBy([], ['createdAt'=> 'DESC']);
        return $this->render('pins/index.html.twig',compact('pins'));
    }

     /**
     * @Route("/pins/create", name="app_pins.create", methods={"GET", "POST"})
     */
     
     public function create(Request $request, EntityManagerInterface $manager):Response
     {
         $pin = new Pin;
        $form= $this->createFormBuilder($pin)
                ->add('title', TextType::class)
                ->add('description', TextareaType::class)
                ->getForm()
                ;
                $form->handleRequest($request);
                if($form->isSubmitted()&& $form->isValid()){
                     $manager->persist($pin);
                     $manager->flush();
                     return $this->redirectToRoute('app.home');

                }

                 return $this->render('pins/create.html.twig',[
                     'form' => $form->createView()
                 ]);
     }
      /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins.edit", methods={"GET", "POST"})
     */
      public function edit(Pin $pin, Request $request, EntityManagerInterface $manager): Response{
          $form= $this->createFormBuilder($pin)
                ->add('title', TextType::class)
                ->add('description', TextareaType::class)
                ->getForm()
                ;
          $form->handleRequest($request);
          if ($form->isSubmitted()&& $form->isValid()) {
              $manager->flush();
              return $this->redirectToRoute('app.home');
             
          }
          return $this->render('pins/edit.html.twig', [
            'pin'=> $pin,
           'form' => $form->createView()
           
       ]);
      }
     /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins.show", methods="GET")
     */
     public function show(Pin $pin): Response
     {
           return $this->render('pins/show.html.twig', compact('pin'));

     }
}
