<?php

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - http://zikula.org/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\PLTheme\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler;

class InstallAdminLteAssets extends ScriptHandler
{

    /**
     * @var array
     * The list of assets. [[vendorPath => destinationPath]]
     */
    private static $dirs= [
        '/almasaeed2010/adminlte/dist' => '/almasaeed2010/adminlte/dist',
        '/almasaeed2010/adminlte/plugins' => '/almasaeed2010/adminlte/plugins',
    ];

    public static function install(Event $event)
    {
        $options = static::getOptions($event);
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $webDir = $options['symfony-web-dir'];

        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'manually install assets')) {
            return;
        }
        if (!static::hasDirectory($event, 'vendor-dir', $vendorDir, 'manually install assets')) {
            return;
        }

        $fs = new Filesystem();
        $event->getIO()->write('<info>Zikula\PLTheme manually installing assets:</info>');

        foreach (static::$dirs as $fromDir => $toDir) {

            $source = $vendorDir . $fromDir;
            $target = $webDir . $toDir;
            if (file_exists($target)) {
                $fs->remove($target);
            }
            $fs->mkdir($target);

            $directoryIterator = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($iterator as $item) {
                if ($item->isDir()) {
                        $fs->mkdir($target . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                } else {
                        $fs->copy($item, $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
                //$event->getIO()->write(sprintf('Installed <comment>%s</comment> to <comment>%s</comment>', $source , $iterator->getSubPathName()));
            }

            $event->getIO()->write(sprintf('Installed <comment>%s</comment> to <comment>%s</comment>', $source , $target));
        }
    }
}
