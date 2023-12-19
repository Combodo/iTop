<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface FormTypeInterface
{
    /**
     * Returns the name of the parent type.
     *
     * The parent type and its extensions will configure the form with the
     * following methods before the current implementation.
     *
     * @return string|null
     */
    public function getParent();

    /**
     * Configures the options for this type.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param array<string, mixed> $options
     *
     * @return void
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options);

    /**
     * Builds the form view.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the view.
     *
     * A view of a form is built before the views of the child forms are built.
     * This means that you cannot access child views in this method. If you need
     * to do so, move your logic to {@link finishView()} instead.
     *
     * @param array<string, mixed> $options
     *
     * @return void
     *
     * @see FormTypeExtensionInterface::buildView()
     */
    public function buildView(FormView $view, FormInterface $form, array $options);

    /**
     * Finishes the form view.
     *
     * This method gets called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the view.
     *
     * When this method is called, views of the form's children have already
     * been built and finished and can be accessed. You should only implement
     * such logic in this method that actually accesses child views. For everything
     * else you are recommended to implement {@link buildView()} instead.
     *
     * @param array<string, mixed> $options
     *
     * @return void
     *
     * @see FormTypeExtensionInterface::finishView()
     */
    public function finishView(FormView $view, FormInterface $form, array $options);

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string
     */
    public function getBlockPrefix();
}
