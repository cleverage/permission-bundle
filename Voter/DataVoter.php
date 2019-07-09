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
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Allows the access to data based on it's class
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class DataVoter implements VoterInterface
{
    /** @var ClassVoter */
    protected $classVoter;

    /**
     * @param ClassVoter $classVoter
     */
    public function __construct(ClassVoter $classVoter)
    {
        $this->classVoter = $classVoter;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (\is_object($object)) {
            return $this->classVoter->vote($token, \get_class($object), $attributes);
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
