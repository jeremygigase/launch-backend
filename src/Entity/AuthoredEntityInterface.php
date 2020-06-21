<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setUser(UserInterface $user): AuthoredEntityInterface;
}