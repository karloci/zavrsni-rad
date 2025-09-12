<?php

namespace App\Service;

use App\Module\Authentication\Repository\RefreshTokenRepository;
use App\Serializer\DataSerializer;
use App\Entity\RefreshToken;
use App\Entity\User;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Random\RandomException;
use RuntimeException;
use stdClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenService
{
    private string $tokenSecret;
    private Security $security;
    private RefreshTokenRepository $refreshTokenRepository;
    private DataSerializer $dataSerializer;

    public function __construct(string $tokenSecret, Security $security, RefreshTokenRepository $refreshTokenRepository, DataSerializer $dataSerializer)
    {
        $this->tokenSecret = $tokenSecret;
        $this->security = $security;
        $this->refreshTokenRepository = $refreshTokenRepository;
        $this->dataSerializer = $dataSerializer;
    }

    public function revokeRefreshToken(string $token, bool $withFlush = true): void
    {
        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!is_null($user)) {
            foreach ($user->getRefreshTokens() as $refreshToken) {
                if ($refreshToken->getToken() === $token) {
                    $this->refreshTokenRepository->delete($refreshToken, $withFlush);
                }
            }
        }
    }

    public function extractAccessTokenFromRequest(Request $request): ?string
    {
        if ($request->headers->has("Authorization")) {
            $authorizationHeader = $request->headers->get("Authorization");

            if (str_starts_with($authorizationHeader, "Bearer")) {
                return substr($authorizationHeader, 7); // strlen("Bearer ") => 7
            }
        }

        if ($request->cookies->has("accessToken")) {
            return $request->cookies->get("accessToken");
        }

        return null;
    }

    public function extractRefreshTokenFromRequest(Request $request): ?string
    {
        $payload = json_decode($request->getContent(), true);

        if (!is_null($payload) && array_key_exists("refreshToken", $payload)) {
            return $payload["refreshToken"];
        }

        if ($request->cookies->has("refreshToken")) {
            return $request->cookies->get("refreshToken");
        }

        return null;
    }

    public function provideAuthenticationResponse(User $user): JsonResponse
    {
        $accessToken = $this->generateAccessToken($user);
        $refreshToken = $this->generateRefreshToken($user);

        $data = $this->dataSerializer->serialize([
            "accessToken"  => $accessToken,
            "refreshToken" => $refreshToken,
            "user"         => $user,
            "farms"        => is_null($user->getFarm()) ? [] : [$user->getFarm()]
        ], ["user:default", "farm:default"]);

        $response = JsonResponse::fromJsonString($data, Response::HTTP_OK);

        $response->headers->setCookie($this->getAccessTokenCookie($accessToken));
        $response->headers->setCookie($this->getRefreshTokenCookie($refreshToken));

        return $response;
    }

    public function generateAccessToken(User $user): string
    {
        $issuedAt = new DateTime();
        $expiresAt = clone $issuedAt;
        $expiresAt->add(DateInterval::createFromDateString("+5 minutes"));
        $expiresAt = DateTimeImmutable::createFromMutable($expiresAt);
        $issuedAt = DateTimeImmutable::createFromMutable($issuedAt);

        return $this->encodeToken($user, $issuedAt, $expiresAt);
    }

    public function encodeToken(
        User $user,
        DateTimeImmutable $issuedAt,
        DateTimeImmutable $expiresAt
    ): string
    {
        try {
            return JWT::encode([
                "iss" => "https://api.zavrsni-rad.com",
                "aud" => "https://app.zavrsni-rad.com",
                "sub" => $user->getUserIdentifier(),
                "iat" => $issuedAt->getTimestamp(),
                "exp" => $expiresAt->getTimestamp(),
                "jti" => bin2hex(random_bytes(16))
            ], $this->tokenSecret, "HS256");
        }
        catch (RandomException $e) {
            throw new RuntimeException("Failed to generate token", 0, $e);
        }
    }

    public function generateRefreshToken(User $user): string
    {
        $issuedAt = new DateTime();
        $expiresAt = clone $issuedAt;
        $expiresAt->add(DateInterval::createFromDateString("+1 month"));
        $expiresAt = DateTimeImmutable::createFromMutable($expiresAt);
        $issuedAt = DateTimeImmutable::createFromMutable($issuedAt);

        $token = $this->encodeToken($user, $issuedAt, $expiresAt);

        $refreshToken = new RefreshToken();
        $refreshToken->setUser($user);
        $refreshToken->setToken($token);
        $refreshToken->setExpiresAt($expiresAt);
        $this->refreshTokenRepository->save($refreshToken, true);

        return $token;
    }

    public function getAccessTokenCookie(?string $token): Cookie
    {
        $expiration = is_null($token) ? time() : $this->decodeToken($token)?->exp;

        return new Cookie(
            name: "accessToken",
            value: $token,
            expire: $expiration,
            secure: true,
            sameSite: Cookie::SAMESITE_NONE
        );
    }

    public function decodeToken(string $token): ?stdClass
    {
        try {
            return JWT::decode($token, new Key($this->tokenSecret, "HS256"));
        }
        catch (SignatureInvalidException|ExpiredException) {
            return null;
        }
    }

    public function getRefreshTokenCookie(?string $token): Cookie
    {
        $expiration = is_null($token) ? time() : $this->decodeToken($token)?->exp;

        return new Cookie(
            name: "refreshToken",
            value: $token,
            expire: $expiration,
            secure: true,
            sameSite: Cookie::SAMESITE_NONE
        );
    }
}
