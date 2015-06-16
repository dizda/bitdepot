<?php

namespace Dizda\Bundle\AppBundle\Composer;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ScriptHandler
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class ScriptHandler
{
    /**
     * Install the needed config files such
     *
     *      - app/config/bitdepot_prod.yml
     */
    public static function installConfigFiles()
    {
        $file       = __DIR__ . '/../../../../../app/config/bitdepot_prod.yml';
        $filesystem = new Filesystem();

        if ($filesystem->exists($file)) {
            return;
        }

        // Creating the file
        $filesystem->dumpFile($file, null);
    }
}
