<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit968853e27dcebad01aa0a353d8659db9
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pejman\\DomParser\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pejman\\DomParser\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit968853e27dcebad01aa0a353d8659db9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit968853e27dcebad01aa0a353d8659db9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit968853e27dcebad01aa0a353d8659db9::$classMap;

        }, null, ClassLoader::class);
    }
}
