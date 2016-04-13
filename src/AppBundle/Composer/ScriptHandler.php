<?php
/**
 * Created by PhpStorm.
 * User: Wickramaranga
 * Date: 4/12/2016
 * Time: 12:39 PM
 */

namespace AppBundle\Composer;

use Composer\Script\CommandEvent;
use Symfony\Component\Filesystem\Filesystem;

class ScriptHandler
{
    public static function prepare(/** @noinspection PhpUndefinedClassInspection */
        CommandEvent $event)
    {

        /** @noinspection PhpUndefinedMethodInspection */
        $extras = $event->getComposer()->getPackage()->getExtra();
        $fs = new Filesystem();

        $mirror_dirs = [
            'twitter bootstrap' => [
                'source' => 'vendor/twbs/bootstrap/dist',
                'destination' => 'web/bundles/bootstrap/'
            ]
        ];

        $copy_assets = [
            'jQuery' => [
                'source' => 'vendor/components/jquery/jquery.js',
                'destination' => 'web/bundles/jquery/jquery.js'
            ],
            'jQuery min' => [
                'source' => 'vendor/components/jquery/jquery.min.js',
                'destination' => 'web/bundles/jquery/jquery.min.js'
            ]
        ];

        // Install assets by mirroring
        foreach ($mirror_dirs as $asset => $data) {
            if ($fs->exists($data['source'])) {
                $fs->mirror($data['source'], $data['destination']);
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Mirrored: ' . $asset);
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Error: ' . $asset . ' not found.');
            }
        }

        // Install assets by copying
        foreach ($copy_assets as $asset => $data) {
            if ($fs->exists($data['source'])) {
                $fs->copy($data['source'], $data['destination']);
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Copied: ' . $asset);
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Error: ' . $asset . ' not found.');
            }
        }
    }
}