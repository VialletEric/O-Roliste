<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ROLE_USER', 'USER_EDIT', 'IS_USER','IS_NOT_USER'])
            && $subject instanceof \App\Entity\User;
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
            case 'ROLE_USER':
                if (in_array('ROLE_USER', $user->getRoles())) {
                    return true;
                    break;
                }
            case 'USER_EDIT':
                if ($subject->getId() === $user->getId()) {
                    return true;
                    break;
                }
            case 'IS_USER':
                if ($subject->getId() === $user->getId()) {
                    return true;
                    break;
                }
            case 'IS_NOT_USER':
                if ($subject->getId() !== $user->getId()) {
                    return true;
                    break;
                }
        }
        return false;
    }
}
