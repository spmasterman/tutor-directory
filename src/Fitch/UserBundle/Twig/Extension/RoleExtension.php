<?php

namespace Fitch\UserBundle\Twig\Extension;

use Fitch\UserBundle\Model\Role;
use Symfony\Component\Translation\IdentityTranslator;

class RoleExtension extends \Twig_Extension
{
    protected $translator;

    /**
     * Constructor method.
     *
     * @param IdentityTranslator $translator
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('humanise_role', array($this, 'humaniseRoleFilter')),
        );
    }

    public function humaniseRoleFilter($role)
    {
        return Role::toHuman($role);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'humanise_role_extension';
    }
}
