<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LogoutEvenSubsciberSubscriber implements EventSubscriberInterface
{
 private $urlGenerator;

     public function __construct(UrlGeneratorInterface $urlGenerator)
     {
         $this->urlGenerator=$urlGenerator;
     }
    public function onLogoutEvent(LogoutEvent $event)
    {

        //message flash
        $session = new Session();
        $session->getFlashBag()->add('success', 'logged out successfully');
         $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app.home')));
      
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
