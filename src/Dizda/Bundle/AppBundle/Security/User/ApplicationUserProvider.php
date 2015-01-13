<?php

namespace Dizda\Bundle\AppBundle\Security\User;

use Dizda\Bundle\AppBundle\Entity\Application;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class ApplicationUserProvider
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ApplicationUserProvider implements UserProviderInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $applicationRepository;

    /**
     * @param EntityRepository $applicationRepository
     */
    public function __construct(EntityRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $application = $this->applicationRepository->findOneByAppId($username);

        if (!$application) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $application;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Application) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getAppId());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Dizda\Bundle\AppBundle\Entity\Application';
    }
}
