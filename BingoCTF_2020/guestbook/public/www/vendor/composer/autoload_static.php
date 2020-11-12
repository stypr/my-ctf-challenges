<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4b854e8577c2c5b423a40492b859297d
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SleekDB\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SleekDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/rakibtg/sleekdb/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4b854e8577c2c5b423a40492b859297d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4b854e8577c2c5b423a40492b859297d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
