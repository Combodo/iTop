<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

trigger_deprecation('symfony/framework-bundle', '6.4', 'The "%s" class is deprecated, use "%s" instead.', LoggingTranslatorPass::class, \Symfony\Component\Translation\DependencyInjection\LoggingTranslatorPass::class);

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 *
 * @deprecated since Symfony 6.4, use Symfony\Component\Translation\DependencyInjection\LoggingTranslatorPass instead.
 */
class LoggingTranslatorPass implements CompilerPassInterface
{
    /**
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasAlias('logger') || !$container->hasAlias('translator')) {
            return;
        }

        if ($container->hasParameter('translator.logging') && $container->getParameter('translator.logging')) {
            $translatorAlias = $container->getAlias('translator');
            $definition = $container->getDefinition((string) $translatorAlias);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());

            if (!$r = $container->getReflectionClass($class)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $class, $translatorAlias));
            }
            if ($r->isSubclassOf(TranslatorInterface::class) && $r->isSubclassOf(TranslatorBagInterface::class)) {
                $container->getDefinition('translator.logging')->setDecoratedService('translator');
                $warmer = $container->getDefinition('translation.warmer');
                $subscriberAttributes = $warmer->getTag('container.service_subscriber');
                $warmer->clearTag('container.service_subscriber');

                foreach ($subscriberAttributes as $k => $v) {
                    if ((!isset($v['id']) || 'translator' !== $v['id']) && (!isset($v['key']) || 'translator' !== $v['key'])) {
                        $warmer->addTag('container.service_subscriber', $v);
                    }
                }
                $warmer->addTag('container.service_subscriber', ['key' => 'translator', 'id' => 'translator.logging.inner']);
            }
        }
    }
}
