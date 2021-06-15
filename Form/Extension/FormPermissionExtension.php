<?php declare(strict_types=1);
/*
 * This file is part of the CleverAge/PermissionBundle package.
 *
 * Copyright (c) 2015-2021 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\PermissionBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Checks if the user is allowed to access or edit a form component
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class FormPermissionExtension extends AbstractTypeExtension
{
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                if (null === $options['read_permissions']) {
                    return;
                }
                if ($this->authorizationChecker->isGranted($options['read_permissions'])) {
                    return;
                }

                $name = $event->getForm()->getName();
                $parentForm = $event->getForm()->getParent();
                if (!$parentForm) {
                    throw new \LogicException("Missing parent form for widget {$name}");
                }
                $parentForm->remove($name);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'read_permissions' => null,
                'edit_permissions' => null,
            ]
        );

        $resolver->setAllowedTypes('read_permissions', ['null', 'array']);
        $resolver->setAllowedTypes('edit_permissions', ['null', 'array']);

        $resolver->setNormalizer(
            'disabled',
            function (Options $options, $value) {
                if (true === $value || null === $options['edit_permissions']) {
                    return $value;
                }

                return !$this->authorizationChecker->isGranted('edit');
            }
        );
    }

    public function getExtendedType(): string
    {
        return FormType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
