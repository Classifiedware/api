<?php

declare(strict_types=1);

namespace App\Controller\AdminApi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthCheckController extends AbstractController
{
    #[Route('/api/admin/auth/check', name: 'api-admin.auth-check', methods: ['GET'])]
    public function authCheck(): Response
    {
        return $this->json([], Response::HTTP_OK);
    }
}
