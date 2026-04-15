<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $user = $this->userRepository->findByEmail($body['email'] ?? '');
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $body['password'] ?? '')) {
            return $this->json(['error' => 'Invalid credentials.'], 401);
        }

        $token = bin2hex(random_bytes(32));
        $user->setApiToken($token);
        $this->userRepository->save($user);

        return $this->json(['token' => $token]);
    }
}
