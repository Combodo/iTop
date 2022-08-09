<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Redirects a request to another URL.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final since version 3.4
 */
class RedirectController implements ContainerAwareInterface
{
    /**
     * @deprecated since version 3.4, to be removed in 4.0
     */
    protected $container;

    private $router;
    private $httpPort;
    private $httpsPort;

    public function __construct(UrlGeneratorInterface $router = null, $httpPort = null, $httpsPort = null)
    {
        $this->router = $router;
        $this->httpPort = $httpPort;
        $this->httpsPort = $httpsPort;
    }

    /**
     * @deprecated since version 3.4, to be removed in 4.0 alongside with the ContainerAwareInterface type.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        @trigger_error(sprintf('The "%s()" method is deprecated since Symfony 3.4 and will be removed in 4.0. Inject an UrlGeneratorInterface using the constructor instead.', __METHOD__), \E_USER_DEPRECATED);

        $this->container = $container;
        $this->router = $container->get('router');
    }

    /**
     * Redirects to another route with the given name.
     *
     * The response status code is 302 if the permanent parameter is false (default),
     * and 301 if the redirection is permanent.
     *
     * In case the route name is empty, the status code will be 404 when permanent is false
     * and 410 otherwise.
     *
     * @param Request    $request          The request instance
     * @param string     $route            The route name to redirect to
     * @param bool       $permanent        Whether the redirection is permanent
     * @param bool|array $ignoreAttributes Whether to ignore attributes or an array of attributes to ignore
     *
     * @return Response A Response instance
     *
     * @throws HttpException In case the route name is empty
     */
    public function redirectAction(Request $request, $route, $permanent = false, $ignoreAttributes = false)
    {
        if ('' == $route) {
            throw new HttpException($permanent ? 410 : 404);
        }

        $attributes = [];
        if (false === $ignoreAttributes || \is_array($ignoreAttributes)) {
            $attributes = $request->attributes->get('_route_params');
            unset($attributes['route'], $attributes['permanent'], $attributes['ignoreAttributes']);
            if ($ignoreAttributes) {
                $attributes = array_diff_key($attributes, array_flip($ignoreAttributes));
            }
        }

        return new RedirectResponse($this->router->generate($route, $attributes, UrlGeneratorInterface::ABSOLUTE_URL), $permanent ? 301 : 302);
    }

    /**
     * Redirects to a URL.
     *
     * The response status code is 302 if the permanent parameter is false (default),
     * and 301 if the redirection is permanent.
     *
     * In case the path is empty, the status code will be 404 when permanent is false
     * and 410 otherwise.
     *
     * @param Request     $request   The request instance
     * @param string      $path      The absolute path or URL to redirect to
     * @param bool        $permanent Whether the redirect is permanent or not
     * @param string|null $scheme    The URL scheme (null to keep the current one)
     * @param int|null    $httpPort  The HTTP port (null to keep the current one for the same scheme or the configured port in the container)
     * @param int|null    $httpsPort The HTTPS port (null to keep the current one for the same scheme or the configured port in the container)
     *
     * @return Response A Response instance
     *
     * @throws HttpException In case the path is empty
     */
    public function urlRedirectAction(Request $request, $path, $permanent = false, $scheme = null, $httpPort = null, $httpsPort = null)
    {
        if ('' == $path) {
            throw new HttpException($permanent ? 410 : 404);
        }

        $statusCode = $permanent ? 301 : 302;

        // redirect if the path is a full URL
        if (parse_url($path, \PHP_URL_SCHEME)) {
            return new RedirectResponse($path, $statusCode);
        }

        if (null === $scheme) {
            $scheme = $request->getScheme();
        }

        if ($qs = $request->server->get('QUERY_STRING') ?: $request->getQueryString()) {
            if (false === strpos($path, '?')) {
                $qs = '?'.$qs;
            } else {
                $qs = '&'.$qs;
            }
        }

        $port = '';
        if ('http' === $scheme) {
            if (null === $httpPort) {
                if ('http' === $request->getScheme()) {
                    $httpPort = $request->getPort();
                } elseif ($this->container && $this->container->hasParameter('request_listener.http_port')) {
                    @trigger_error(sprintf('Passing the http port as a container parameter is deprecated since Symfony 3.4 and won\'t be possible in 4.0. Pass it to the constructor of the "%s" class instead.', __CLASS__), \E_USER_DEPRECATED);
                    $httpPort = $this->container->getParameter('request_listener.http_port');
                } else {
                    $httpPort = $this->httpPort;
                }
            }

            if (null !== $httpPort && 80 != $httpPort) {
                $port = ":$httpPort";
            }
        } elseif ('https' === $scheme) {
            if (null === $httpsPort) {
                if ('https' === $request->getScheme()) {
                    $httpsPort = $request->getPort();
                } elseif ($this->container && $this->container->hasParameter('request_listener.https_port')) {
                    @trigger_error(sprintf('Passing the https port as a container parameter is deprecated since Symfony 3.4 and won\'t be possible in 4.0. Pass it to the constructor of the "%s" class instead.', __CLASS__), \E_USER_DEPRECATED);
                    $httpsPort = $this->container->getParameter('request_listener.https_port');
                } else {
                    $httpsPort = $this->httpsPort;
                }
            }

            if (null !== $httpsPort && 443 != $httpsPort) {
                $port = ":$httpsPort";
            }
        }

        $url = $scheme.'://'.$request->getHost().$port.$request->getBaseUrl().$path.$qs;

        return new RedirectResponse($url, $statusCode);
    }
}
