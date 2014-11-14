<?php

namespace Fitch\TutorBundle\Security\Authorization\Voter;

use Fitch\TutorBundle\Entity\Tutor;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TutorVoter
 *
 * Uses the ROLE_HIERARCHY (currently!) to decide on access to tutor object. We could just use f.ex.
 * isGranted('ROLE_ADMIN', $tutor) rather than isGranted(TutorVoter::FULL_EDIT, $tutor) and not
 * bother with this class (The built in RoleHierarchyVoter would handle it) however, that would
 * bake the role hierarchy -> access relationship all over the codebase. As its far from decided
 * what the roles need to be, this class gives us a level of abstraction. Changing the vote() method
 * should be enough to implement system wide security access changes. It is expected that tutors
 * will eventually be able to edit some of their own details (i.e. rates but not notes), and that
 * departmental managers will be able to edit/access their direct reports etc. The logic is expected
 * to get complex, and this will facilitate it.
 *
 * @package Fitch\TutorBundle\Security\Authorization\Voter
 */
class TutorVoter implements VoterInterface
{
    const VIEW = 'view';
    const LIMITED_EDIT = 'edit';
    const FULL_EDIT = 'all';

    /** @var  RoleHierarchy */
    private $rh;

    public function __construct($roles) {
        $this->rh = new RoleHierarchy($roles);
    }

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::LIMITED_EDIT,
            self::FULL_EDIT,
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'Fitch\TutorBundle\Entity\Tutor';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param Tutor $tutor
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $tutor, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($tutor))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW, LIMITED_EDIT or FULL_EDIT'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        switch($attribute) {
            case self::VIEW:
                return VoterInterface::ACCESS_GRANTED;
                break;
            case self::LIMITED_EDIT:
                if ($this->userHasRole($user, 'ROLE_EDITOR')) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::FULL_EDIT:
                if ($this->userHasRole($user, 'ROLE_ADMIN')) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    private function userHasRole(UserInterface $user, $role) {
        foreach($user->getRoles() as $heldRole) {
            foreach ($this->rh->getReachableRoles([new Role($heldRole)]) as $reachableRole) {
                if ($role === $reachableRole->getRole()) {
                    return true;
                }
            }
        }
        return false;
    }
}