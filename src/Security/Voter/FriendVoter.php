<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FriendVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['FRIEND_ADD', 'FRIEND_DELETE'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'FRIEND_ADD':
                // Si l'utilisateur est un USER, il peut ajouter un ami
                if ($user->getId() === $subject->getId()) {
                    return true;
                }
                break;
            case 'FRIEND_DELETE':
                // Si l'utilisateur est un USER, il peut supprimer un ami
                if ($user->getId() === $subject->getId()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
