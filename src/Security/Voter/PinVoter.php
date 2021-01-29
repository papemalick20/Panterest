<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PinVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_EDIT', 'POST_DELETE'])
            && $subject instanceof \App\Entity\Pin;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
         if ($user !== $subject->getUser()){
             return false;
         }

        // ... (check conditions and return true to grant permission) ...
        switch ($subject) {
            case 'POST_EDIT':
                return $subject->getUser()->getId() == $token->getUser->getId();
                break;
            case 'POST_DELETE':
                return $subject->getUser()->getId()== $token->getUser->getId();
                break;
        }

        return false;
    }
}
