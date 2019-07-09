<?php declare(strict_types=1);
/*
 * This file is part of the CleverAge/PermissionBundle package.
 *
 * Copyright (c) 2015-2019 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\PermissionBundle\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Allows the access to a family based on the family permissions of a user.
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ClassVoter implements VoterInterface
{
    /** @var AccessDecisionManagerInterface */
    protected $decisionManager;

    /** @var array */
    protected $classPermissions = [];

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $class
     * @param array  $permissions
     */
    public function addPermissions(string $class, array $permissions): void
    {
        $this->classPermissions[$class] = $permissions;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function vote(TokenInterface $token, $object, array $attributes): int
    {
        $result = VoterInterface::ACCESS_ABSTAIN;

        if (!\is_string($object)) {
            return $result;
        }

        if (!array_key_exists($object, $this->classPermissions)) {
            return $result;
        }

        $permissions = $this->classPermissions[$object];

        $result = VoterInterface::ACCESS_DENIED;
        foreach ($attributes as $attribute) {
            if (!array_key_exists($attribute, $permissions)) {
                return VoterInterface::ACCESS_GRANTED; // No permissions means access is granted
            }
            if (null === $permissions[$attribute]) {
                return VoterInterface::ACCESS_GRANTED; // Null means access granted
            }

            if ($this->decisionManager->decide($token, (array) $permissions[$attribute])) {
                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return $result;
    }
}
