<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6d7e9fd01d116805764b80a9d0b89437
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6d7e9fd01d116805764b80a9d0b89437::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6d7e9fd01d116805764b80a9d0b89437::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6d7e9fd01d116805764b80a9d0b89437::$classMap;

        }, null, ClassLoader::class);
    }
}
