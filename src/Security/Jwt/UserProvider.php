<?php

namespace OxidCommunity\SecurityBundle\Security\Jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    private static $user = null;

    public function loadUserByUsername($username)
    {
        if (!empty(static::$user)) {
            return static::$user;
        }

        try {
            $token = (new Parser())->parse($this->getBearerToken());
        } catch (\RuntimeException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        } catch (\InvalidArgumentException $e) {
            return null;
        } catch (\BadMethodCallException $e) {
            return null;
        } catch (\OutOfBoundsException $e) {
            return null;
        }

        $validationData = new ValidationData();
        $validationData->setIssuer('oxid-moduleinstaller');
        $validationData->setAudience('oxid-moduleinstaller');

        if($token->validate($validationData)) {
            return static::$user = new User($token);
        }

        return null;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        try {
            $token = (new Parser())->parse($this->getBearerToken());
        } catch (Exception $exception) {
            return false;
        }

        $validationData = new ValidationData();
        $validationData->setIssuer('oxid-moduleinstaller');
        $validationData->setAudience('oxid-moduleinstaller');

        if($token->validate($validationData)) {
            return static::$user = new User($token);
        }

        return null;
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }

    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * Get access token from header
    */
    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
