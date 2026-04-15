<?php

namespace App\Service;

use App\DTO\CreateUserInput;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser(CreateUserInput $input): User
    {
        $user = new User();
        $user->setEmail($input->email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $input->password)
        );

        $this->userRepository->save($user);

        return $user;
    }
}
