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

namespace Lightnote\Module;

/**
 * Module class
 *
 * @property-read $namespace
 */
class Module extends \Lightnote\Attribute
{
    const CONTROLLER_DIR = 'controller';

    const ASSET_DIR = 'asset';
    const ASSET_CSS_DIR = 'css';
    const ASSET_JS_DIR = 'js';
    const ASSET_IMG_DIR = 'img';

    const VIEW_DIR = 'view';

    public static $modulesPath = 'module';


    /**
     *
     * @var string
     */
    protected $namespace = '';

    

    /**
     *
     * @param string $namespace
     * @param stdClass $config
     */
    public function __construct($namespace, $config = null)
    {
        $this->namespace = $namespace;
        
        $this->loadConfig($config);

        $this->setupAutoloader();
    }

    /**
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    protected function loadClass($className)
    {
        $path = self::$modulesPath . \DIRECTORY_SEPARATOR . str_replace('\\', \DIRECTORY_SEPARATOR, $className);
        $path = \preg_replace('/(.+(?:\\\|\/))([^\\/]+Controller)$/', '$1' . self::CONTROLLER_DIR  . '/$2.php', $path);
        
        if(file_exists($path))
        {
            include_once $path;
        }
    }

    /**
     *
     * @param stdClass $config
     */
    protected function loadConfig($config)
    {
        // overwrite this method to load config
    }

    public function setupRoutes(\Lightnote\Routing\RouteCollection $routes, \Lightnote\Mvc\RouteHandler $routeHandler)
    {
        
    }

    public function setupAutoloader()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function setConfig($config = null)
    {
        $this->loadConfig($config);
    }
}