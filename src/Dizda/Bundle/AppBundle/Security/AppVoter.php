<?php

namespace Dizda\Bundle\AppBundle\Security;

use Dizda\Bundle\AppBundle\Entity\Application;
use Dizda\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AppVoter extends Voter
{
    const ACCESS = 'access';

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::ACCESS))) {
            return false;
        }

//        // only vote on Post objects inside this voter
//        if (!$subject instanceof Application || is_int($subject)) {
//            return false;
//        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Application $app */
        $app = $this->entityManager->getRepository('DizdaAppBundle:Application')->find($subject);

        switch ($attribute) {
            case self::ACCESS:
                return $this->canAccess($app, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canAccess(Application $application, UserInterface $user)
    {
        return $user->hasRole('APP_ACCESS_'.$application->getId());
    }
}