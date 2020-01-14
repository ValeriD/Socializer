<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit558e4abe801764dd54bcef8406643f27
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Inc\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Inc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit558e4abe801764dd54bcef8406643f27::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit558e4abe801764dd54bcef8406643f27::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
