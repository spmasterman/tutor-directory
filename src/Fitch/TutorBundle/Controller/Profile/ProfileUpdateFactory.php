<?php

namespace Fitch\TutorBundle\Controller\Profile;

use Fitch\CommonBundle\Exception\ClassNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfileUpdateFactory
{
    /**
     * @param $key
     * @param ContainerInterface $container
     *
     * @return ProfileUpdateInterface
     *
     * @throws ClassNotFoundException
     */
    public static function getUpdater($key, ContainerInterface $container)
    {
        $className =  'Fitch\TutorBundle\Controller\Profile\ProfileUpdate'.self::toCamelCase($key, true);

        if (!class_exists($className)) {
            throw new ClassNotFoundException($className.' not found.');
        }
        $profileUpdateInterface = new $className($container);

        return $profileUpdateInterface;
    }

    /**
     * Translates a string with underscores into camel case (e.g. first_name -> firstName).
     *
     * @param $str
     * @param bool $capitaliseFirstChar
     *
     * @return mixed
     */
    private static function toCamelCase($str, $capitaliseFirstChar = false)
    {
        if ($capitaliseFirstChar) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
