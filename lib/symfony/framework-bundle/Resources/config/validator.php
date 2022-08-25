<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Bundle\FrameworkBundle\CacheWarmer\ValidatorCacheWarmer;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\ExpressionValidator;
use Symfony\Component\Validator\Constraints\NotCompromisedPasswordValidator;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Mapping\Loader\PropertyInfoLoader;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

return static function (ContainerConfigurator $container) {
    $container->parameters()
        ->set('validator.mapping.cache.file', param('kernel.cache_dir').'/validation.php');

    $container->services()
        ->set('validator', ValidatorInterface::class)
            ->public()
            ->factory([service('validator.builder'), 'getValidator'])
            ->tag('container.private', ['package' => 'symfony/framework-bundle', 'version' => '5.2'])
        ->alias(ValidatorInterface::class, 'validator')

        ->set('validator.builder', ValidatorBuilder::class)
            ->factory([Validation::class, 'createValidatorBuilder'])
            ->call('setConstraintValidatorFactory', [
                service('validator.validator_factory'),
            ])
            ->call('setTranslator', [
                service('translator')->ignoreOnInvalid(),
            ])
            ->call('setTranslationDomain', [
                param('validator.translation_domain'),
            ])
        ->alias('validator.mapping.class_metadata_factory', 'validator')

        ->set('validator.mapping.cache_warmer', ValidatorCacheWarmer::class)
            ->args([
                service('validator.builder'),
                param('validator.mapping.cache.file'),
            ])
            ->tag('kernel.cache_warmer')

        ->set('validator.mapping.cache.adapter', PhpArrayAdapter::class)
            ->factory([PhpArrayAdapter::class, 'create'])
            ->args([
                param('validator.mapping.cache.file'),
                service('cache.validator'),
            ])

        ->set('validator.validator_factory', ContainerConstraintValidatorFactory::class)
            ->args([
                abstract_arg('Constraint validators locator'),
            ])

        ->set('validator.expression', ExpressionValidator::class)
            ->args([service('validator.expression_language')->nullOnInvalid()])
            ->tag('validator.constraint_validator', [
                'alias' => 'validator.expression',
            ])

        ->set('validator.expression_language', ExpressionLanguage::class)
            ->args([service('cache.validator_expression_language')->nullOnInvalid()])

        ->set('cache.validator_expression_language')
            ->parent('cache.system')
            ->tag('cache.pool')

        ->set('validator.email', EmailValidator::class)
            ->args([
                abstract_arg('Default mode'),
            ])
            ->tag('validator.constraint_validator', [
                'alias' => EmailValidator::class,
            ])

        ->set('validator.not_compromised_password', NotCompromisedPasswordValidator::class)
            ->args([
                service('http_client')->nullOnInvalid(),
                param('kernel.charset'),
                false,
            ])
            ->tag('validator.constraint_validator', [
                'alias' => NotCompromisedPasswordValidator::class,
            ])

        ->set('validator.property_info_loader', PropertyInfoLoader::class)
            ->args([
                service('property_info'),
                service('property_info'),
                service('property_info'),
            ])
            ->tag('validator.auto_mapper')
    ;
};
