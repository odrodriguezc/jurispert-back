<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['TASK_VIEW', 'TASK_EDIT'])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $project = $subject->getProject();
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'TASK_VIEW':
                if ($project->getOwner() === $user) {
                    return true;
                }

                foreach ($project->getParticipations() as $participation) {
                    if ($participation->getUser() === $user) {
                        return true;
                    }
                }

                return false;

            case 'TASK_EDIT':
                if ($project->getOwner() === $user) {
                    return true;
                }

                foreach ($project->getParticipations() as $participation) {
                    if ($participation->getUser() === $user && ($participation->getRole() === "MANAGER" || $participation->getRole() === "CREATOR")) {
                        return true;
                    }
                }

                return false;
        }

        return false;
    }
}
