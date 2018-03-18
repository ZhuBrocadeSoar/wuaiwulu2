<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
            'think\\' => 6,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/../..' . '/thinkphp/library/think',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}