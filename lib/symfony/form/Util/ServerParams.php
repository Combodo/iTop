<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Util;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ServerParams
{
    private ?RequestStack $requestStack;

    public function __construct(RequestStack $requestStack = null)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Returns true if the POST max size has been exceeded in the request.
     */
    public function hasPostMaxSizeBeenExceeded(): bool
    {
        $contentLength = $this->getContentLength();
        $maxContentLength = $this->getPostMaxSize();

        return $maxContentLength && $contentLength > $maxContentLength;
    }

    /**
     * Returns maximum post size in bytes.
     */
    public function getPostMaxSize(): int|float|null
    {
        $iniMax = strtolower($this->getNormalizedIniPostMaxSize());

        if ('' === $iniMax) {
            return null;
        }

        $max = ltrim($iniMax, '+');
        if (str_starts_with($max, '0x')) {
            $max = \intval($max, 16);
        } elseif (str_starts_with($max, '0')) {
            $max = \intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($iniMax, -1)) {
            case 't': $max *= 1024;
                // no break
            case 'g': $max *= 1024;
                // no break
            case 'm': $max *= 1024;
                // no break
            case 'k': $max *= 1024;
        }

        return $max;
    }

    /**
     * Returns the normalized "post_max_size" ini setting.
     */
    public function getNormalizedIniPostMaxSize(): string
    {
        return strtoupper(trim(\ini_get('post_max_size')));
    }

    /**
     * Returns the content length of the request.
     */
    public function getContentLength(): mixed
    {
        if (null !== $this->requestStack && null !== $request = $this->requestStack->getCurrentRequest()) {
            return $request->server->get('CONTENT_LENGTH');
        }

        return isset($_SERVER['CONTENT_LENGTH'])
            ? (int) $_SERVER['CONTENT_LENGTH']
            : null;
    }
}
