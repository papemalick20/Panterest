<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app.home")
     */
    public function home(PinRepository $pinRepository): Response
    {
        $pin =  $pinRepository->findAll();
        return $this->render('pins/index.html.twig',[
            'pins' =>$pin
        ]);
    }
     /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins.show")
     */
     public function show(Pin $pin): Response
     {
           return $this->render('pins/show.html.twig', compact('pin'));

     }
}
