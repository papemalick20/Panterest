<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods="GET")
     * isGranted("IS_AUTHENTICATED_FULLY")
     */
    public function show(): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET", "POST"})
     * @isGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }
        $user = $this->getUser();
        $form =$this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           $em->flush();
           $this->addFlash('success', 'Account updated successfully');
           return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig', [
            'form'=> $form->createView()
        ]);
    }

     /**
     * @Route("/account/change-password", name="app_account_change_password", methods={"GET", "POST"})
     * @isGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $password): Response
    {
        // if(! $this->getUser()){
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class,null, [
            'current_password_is_required'=>true
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        $user->setPassword(
         $password->encodePassword($user,$form['plainPassword']->getData())

        );
        $em->flush();
        $this->addFlash('success', 'password updated successfully');
        return $this->redirectToRoute('app_account');

        }
        return $this->render('account/change_password.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
