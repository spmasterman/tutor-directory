<?php

namespace Fitch\TutorBundle\Controller\TutorLanguage;

use Fitch\CommonBundle\Exception\ClassNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TutorLanguageUpdateFactory
{
    /**
     * @param $key
     * @param ContainerInterface $container
     *
     * @return TutorLanguageUpdateInterface
     *
     * @throws ClassNotFoundException
     */
    public static function getUpdater($key, ContainerInterface $container)
    {
        $className =  'Fitch\TutorBundle\Controller\TutorLanguage\TutorLanguageUpdate'.self::toClassNameSuffix($key);

        if (!class_exists($className)) {
            throw new ClassNotFoundException($className.' not found.');
        }
        $tutorLanguageUpdateInterface = new $className($container);

        return $tutorLanguageUpdateInterface;
    }

    /**
     * Translates a string prefixed with tutor-language-something to Something,
     * or tutor-language to Language
     *
     * @param $str
     *
     * @return mixed
     */
    private static function toClassNameSuffix($str)
    {
        if ($str == 'tutor-language') {
            return 'Language';
        }

        $prefix = 'tutor-language-';
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }
        return ucfirst($str);
    }
}
