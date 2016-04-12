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

        $web_assets = [
            'bootstrap' => ['source' => 'vendor/twbs/bootstrap/dist', 'destination' => 'web/bundles/framework/'],
            'site images' => ['source' => 'src/AppBundle/Resources/public', 'destination' => 'web/bundles/framework/']
        ];

        // Install web assets
        foreach ($web_assets as $asset => $data) {
            if ($fs->exists($data['source'])) {
                $fs->mirror($data['source'], $data['destination']);
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Mirrored: ' . $asset);
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                $event->getIO()->write('Error: ' . $asset . ' not found.');
            }
        }
    }
}