<?php

namespace Fitch\TutorBundle\Entity;

use Fitch\UserBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;

trait ProvenanceBuilderTrait
{

    /**
     * Calculates a Provenance String (i.e. the Author/Editor, Created/Uploaded, Edited)
     *
     * i.e. (Edited Mar 9) Shaun Masterman on Mar 1
     *
     * @param User|null $user
     *
     * @return string
     */
    protected function generateProvenanceString($user)
    {
        if ($user) {
            $fullName = $user->getFullName();
            $string = $fullName ?: $user->getUsername();
        } else {
            $string = 'Anonymous';
        }

        $string .= ' on '.$this->getCreated()->format('M d, Y');

        // give an hours grace before we mark something as edited
        $timestampTransformer = new DateTimeToTimestampTransformer();
        $editedTime = $timestampTransformer->transform($this->getUpdated())
            - $timestampTransformer->transform($this->getCreated());

        if ($editedTime > 3600) {
            $string = '(Edited '.$this->getUpdated()->format('M d, Y').') '.$string;
        }

        return $string;
    }
}