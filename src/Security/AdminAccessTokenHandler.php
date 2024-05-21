<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Admin;
use App\Entity\AdminAccessToken;
use App\Repository\AdminAccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AdminAccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private AdminAccessTokenRepository $accessTokenRepository)
    {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $accessToken = $this->accessTokenRepository->findOneBy(['token' => $accessToken]);
        if (!$accessToken instanceof AdminAccessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        $admin = $accessToken->getAdmin();
        if (!$admin instanceof Admin) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($admin->getEmail());
    }
}