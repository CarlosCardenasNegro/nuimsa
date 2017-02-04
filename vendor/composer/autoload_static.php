<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf7167a1ced35d860c20a636651aa2f87
{
    public static $prefixLengthsPsr4 = array (
        'n' => 
        array (
            'nuimsa\\clases\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'nuimsa\\clases\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/clases',
        ),
    );

    public static $prefixesPsr0 = array (
        'G' => 
        array (
            'Gregwar\\Image' => 
            array (
                0 => __DIR__ . '/..' . '/gregwar/image',
            ),
            'Gregwar\\Cache' => 
            array (
                0 => __DIR__ . '/..' . '/gregwar/cache',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf7167a1ced35d860c20a636651aa2f87::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf7167a1ced35d860c20a636651aa2f87::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitf7167a1ced35d860c20a636651aa2f87::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
