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
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'J' => 
        array (
            'Jenssegers\\Agent\\' => 17,
            'Jaybizzle\\CrawlerDetect\\' => 24,
        ),
        'I' => 
        array (
            'Identicon\\' => 10,
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
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Jenssegers\\Agent\\' => 
        array (
            0 => __DIR__ . '/..' . '/jenssegers/agent/src',
        ),
        'Jaybizzle\\CrawlerDetect\\' => 
        array (
            0 => __DIR__ . '/..' . '/jaybizzle/crawler-detect/src',
        ),
        'Identicon\\' => 
        array (
            0 => __DIR__ . '/..' . '/yzalis/identicon/src/Identicon',
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
        'D' => 
        array (
            'Detection' => 
            array (
                0 => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/namespaced',
            ),
        ),
    );

    public static $classMap = array (
        'GeetestLib' => __DIR__ . '/..' . '/gee-team/gt-php-sdk/lib/class.geetestlib.php',
        'Mobile_Detect' => __DIR__ . '/..' . '/mobiledetect/mobiledetectlib/Mobile_Detect.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit87cb1b67d239d2313e2a1f9ccc423c29::$classMap;

        }, null, ClassLoader::class);
    }
}
