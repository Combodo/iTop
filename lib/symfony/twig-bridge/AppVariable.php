<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Exposes some Symfony parameters and services as an "app" global variable.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class AppVariable
{
    private $tokenStorage;
    private $requestStack;
    private $environment;
    private $debug;

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function setEnvironment(string $environment)
    {
        $this->environment = $environment;
    }

    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Returns the current token.
     *
     * @return TokenInterface|null
     *
     * @throws \RuntimeException When the TokenStorage is not available
     */
    public function getToken()
    {
        if (null === $tokenStorage = $this->tokenStorage) {
            throw new \RuntimeException('The "app.token" variable is not available.');
        }

        return $tokenStorage->getToken();
    }

    /**
     * Returns the current user.
     *
     * @return UserInterface|null
     *
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (null === $tokenStorage = $this->tokenStorage) {
            throw new \RuntimeException('The "app.user" variable is not available.');
        }

        if (!$token = $tokenStorage->getToken()) {
            return null;
        }

        $user = $token->getUser();

        // @deprecated since Symfony 5.4, $user will always be a UserInterface instance
        return \is_object($user) ? $user : null;
    }

    /**
     * Returns the current request.
     *
     * @return Request|null
     */
    public function getRequest()
    {
        if (null === $this->requestStack) {
            throw new \RuntimeException('The "app.request" variable is not available.');
        }

        return $this->requestStack->getCurrentRequest();
    }

    /**
     * Returns the current session.
     *
     * @return Session|null
     */
    public function getSession()
    {
        if (null === $this->requestStack) {
            throw new \RuntimeException('The "app.session" variable is not available.');
        }
        $request = $this->getRequest();

        return $request && $request->hasSession() ? $request->getSession() : null;
    }

    /**
     * Returns the current app environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        if (null === $this->environment) {
            throw new \RuntimeException('The "app.environment" variable is not available.');
        }

        return $this->environment;
    }

    /**
     * Returns the current app debug mode.
     *
     * @return bool
     */
    public function getDebug()
    {
        if (null === $this->debug) {
            throw new \RuntimeException('The "app.debug" variable is not available.');
        }

        return $this->debug;
    }

    /**
     * Returns some or all the existing flash messages:
     *  * getFlashes() returns all the flash messages
     *  * getFlashes('notice') returns a simple array with flash messages of that type
     *  * getFlashes(['notice', 'error']) returns a nested array of type => messages.
     *
     * @return array
     */
    public function getFlashes($types = null)
    {
        try {
            if (null === $session = $this->getSession()) {
                return [];
            }
        } catch (\RuntimeException $e) {
            return [];
        }

        if (null === $types || '' === $types || [] === $types) {
            return $session->getFlashBag()->all();
        }

        if (\is_string($types)) {
            return $session->getFlashBag()->get($types);
        }

        $result = [];
        foreach ($types as $type) {
            $result[$type] = $session->getFlashBag()->get($type);
        }

        return $result;
    }
}
