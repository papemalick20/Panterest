<?php

namespace App\Controller;

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
}
