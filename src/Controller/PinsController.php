<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app.home", methods="GET")
     */
    public function home(PinRepository $pinRepository): Response
    {
        $pins =  $pinRepository->findBy([], ['createdAt'=> 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
    * @Route("/pins/create", name="app_pins.create", methods={"GET", "POST"})
    * @Security("is_granted('ROLE_USER') and user.isVerified()")
    */
     
    public function create(Request $request, EntityManagerInterface $manager, UserRepository $repo):Response
    {
        
        $pin = new Pin;
        $form= $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $pin->setUser($this->getUser());
            $manager->persist($pin);
            $manager->flush();
            $this->addFlash('success', 'Pin successfully created!');
            return $this->redirectToRoute('app.home');
        }

        return $this->render('pins/create.html.twig', [
                     'form' => $form->createView()
                 ]);
    }
    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins.edit", methods={"GET", "PUT"})
     *@isGranted("PIN_MANAGE", subject ="pin")
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $manager): Response
    {
        
        
        $form= $this->createForm(PinType::class, $pin, [
              'method'=> 'PUT'
          ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Pin successfully updated!');
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
    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins.delete", methods="DELETE")
     * @isGranted("PIN_MANAGE", subject ="pin")
     
     */
    public function delete(Pin $pin,  Request $request, EntityManagerInterface $manager)
    {
    
       if ($this->isCsrfTokenValid('pin_deletion_'. $pin->getId(), $request->request->get('csrf_token'))){
        $manager->remove($pin);
        $manager->flush();
        
        $this->addFlash('info', 'Pin successfully deleted!');
       }
        return $this->redirectToRoute('app.home');
    }
}
