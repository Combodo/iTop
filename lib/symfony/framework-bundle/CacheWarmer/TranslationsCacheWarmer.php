<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\CacheWarmer;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Generates the catalogues for translations.
 *
 * @author Xavier Leune <xavier.leune@gmail.com>
 */
class TranslationsCacheWarmer implements CacheWarmerInterface, ServiceSubscriberInterface
{
    private ContainerInterface $container;
    private TranslatorInterface $translator;

    public function __construct(ContainerInterface $container)
    {
        // As this cache warmer is optional, dependencies should be lazy-loaded, that's why a container should be injected.
        $this->container = $container;
    }

    /**
     * @param string|null $buildDir
     */
    public function warmUp(string $cacheDir /* , string $buildDir = null */): array
    {
        $this->translator ??= $this->container->get('translator');

        if ($this->translator instanceof WarmableInterface) {
            $buildDir = 1 < \func_num_args() ? func_get_arg(1) : null;

            return (array) $this->translator->warmUp($cacheDir, $buildDir);
        }

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }

    public static function getSubscribedServices(): array
    {
        return [
            'translator' => TranslatorInterface::class,
        ];
    }
}
