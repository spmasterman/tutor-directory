<?php

namespace Fitch\TutorBundle\Controller\Competency;

use Fitch\CommonBundle\Exception\ClassNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CompetencyUpdateFactory
{
    /**
     * @param $key
     * @param ContainerInterface $container
     *
     * @return CompetencyUpdateInterface
     *
     * @throws ClassNotFoundException
     */
    public static function getUpdater($key, ContainerInterface $container)
    {
        $className =  'Fitch\TutorBundle\Controller\Competency\CompetencyUpdate'.self::toClassNameSuffix($key);

        if (!class_exists($className)) {
            throw new ClassNotFoundException($className.' not found.');
        }
        $competencyUpdateInterface = new $className($container);

        return $competencyUpdateInterface;
    }

    /**
     * Translates a string prefixed with competency-something to Something.
     *
     * @param $str
     *
     * @return mixed
     */
    private static function toClassNameSuffix($str)
    {
        $prefix = 'competency-';

        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }

        return ucfirst($str);
    }
}
