<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use App\Entity\Admin;
use App\Entity\AdminAccessToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;

class AdminApiLoginController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/admin/login', name: 'api_admin_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?Admin $user): JsonResponse
    {
        if (!$user instanceof Admin) {
            return $this->json([], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $this->createAccessToken($user),
        ]);
    }

    private function createAccessToken(Admin $admin): string
    {
        $token = bin2hex(random_bytes(16));

        $adminAccessToken = new AdminAccessToken();
        $adminAccessToken->setUuid(Uuid::v4());
        $adminAccessToken->setToken($token);
        $adminAccessToken->setAdmin($admin);
        $adminAccessToken->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($adminAccessToken);
        $this->entityManager->flush();

        return $adminAccessToken->getToken();
    }
}
