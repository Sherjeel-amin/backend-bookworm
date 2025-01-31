<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51b490a74939f228a99e03f8c9a08246
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51b490a74939f228a99e03f8c9a08246::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51b490a74939f228a99e03f8c9a08246::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit51b490a74939f228a99e03f8c9a08246::$classMap;

        }, null, ClassLoader::class);
    }
}
