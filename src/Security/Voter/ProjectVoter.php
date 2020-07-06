<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PROJECT_VIEW', 'PROJECT_EDIT'])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PROJECT_VIEW':
                if ($subject->getOwner() === $user) {
                    return true;
                }

                foreach ($subject->getParticipations() as $participation) {
                    if ($participation->getUser() === $user) {
                        return true;
                    }
                }

                return false;

            case 'PROJECT_EDIT':
                if ($subject->getOwner() === $user) {
                    return true;
                }

                foreach ($subject->getParticipations() as $participation) {
                    if ($participation->getUser() === $user && ($participation->getRole() === "MANAGER" || $participation->getRole() === "CREATOR")) {
                        return true;
                    }
                }

                return false;
        }

        return false;
    }
}
