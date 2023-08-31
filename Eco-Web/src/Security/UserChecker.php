<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->getIsVerified()) {
            throw new CustomUserMessageAccountStatusException('Vous devez vérifier votre adresse e-mail avant de pouvoir vous connecter.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}

