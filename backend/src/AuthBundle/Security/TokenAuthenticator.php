<?php

namespace App\AuthBundle\Security;

use App\AuthBundle\Exception\InvalidTokenException;
use App\AuthBundle\Exception\TokenExpiredException;
use App\AuthBundle\Service\TokenService;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $apiToken = $request->headers->get('Authorization');

        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException(
                'No API token provided',
                [],
                Response::HTTP_UNAUTHORIZED);
        }

        $apiToken = $this->parseToken($apiToken);

        if (!$this->tokenService->findAccessToken($apiToken)) {
            throw new InvalidTokenException('Invalid token', Response::HTTP_UNAUTHORIZED);
        }

        if ($this->tokenService->isAccessTokenExpired($apiToken)) {
            throw new TokenExpiredException('Token is expired', Response::HTTP_UNAUTHORIZED);
        }

        return new SelfValidatingPassport(new UserBadge($apiToken));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

    protected function parseToken(string $token): string
    {
        return str_replace('Bearer ', '', $token);
    }
}
