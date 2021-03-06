<?php
/*
 * Copyright (c) 2007-2010, Dmitry Monin <dmitry.monin [at] lightnote [dot] org>
 * Permission to use and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * You should have received a copy of the Lightnote CMS End-User License Agreement
 * along with this program.  If not, see <http://www.lightnote.org/license.html>.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL
 * WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR
 * BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES
 * OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS,
 * WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION,
 * ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS
 * SOFTWARE.
 */

namespace Lightnote;

/**
 * Loader class
 *
 *
 */
class Loader
{
    const MODULE_CONTROLLER_DIR = 'controller';

    public static $modulesPath = '';
    private static $lookupDirs = array(
        array(
            'dir' => LIBRARY_PATH,
            'prefix' => ''
        )
    );

    public static function addLookupDirectory($dir, $prefix = '')
    {
        self::$lookupDirs[] = array(
            'dir' => $dir,
            'prefix' => $prefix
        );

    }

    public static function load($className)
    {
        foreach(self::$lookupDirs as $lookupDir)
        {
            $file = $lookupDir['dir'] . \DIRECTORY_SEPARATOR . str_replace('\\', \DIRECTORY_SEPARATOR, $className) . '.php';
            if(\file_exists($file))
            {
                include_once($file);
                return true;
            }
        }

        return self::loadModuleController($className);
    }

    private static function loadModuleController($className)
    {
        $path = self::$modulesPath . \DIRECTORY_SEPARATOR . str_replace('\\', \DIRECTORY_SEPARATOR, $className);
        $path = \preg_replace('/(.+(?:\\\|\/))([^\\/]+Controller)$/', '$1' . self::MODULE_CONTROLLER_DIR  . '/$2.php', $path);
        
        if(file_exists($path))
        {
            include_once $path;
            $className::$path = dirname($path);

            return true;
        }

        return false;
    }
}