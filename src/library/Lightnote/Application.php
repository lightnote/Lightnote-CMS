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
 * Application class
 *
 * @property-read $httpContext
 * @property-read $environment
 * @property-read $config
 */
class Application extends Attribute
{
    /**
     *
     * @var string
     */
    private $environment = 'production';

    /**
     *
     * @var Bootstrap
     */
    private $bootstrap = null;
    
    /**
     *
     * @var Config
     */
    private $config = null;

    /**
     *
     * @var Http\HttpContext
     */
    private $httpContext = null;

    public function __construct()
    {
        
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getHttpContext()
    {
        if($this->httpContext)
        {
            return $this->httpContext;
        }
        
        $this->httpContext = new Http\HttpContext();        
        $this->httpContext->session = new Session();
        $this->httpContext->request = $this->getRequest();

        return $this->httpContext;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    private function getRequest()
    {
        if(\php_sapi_name () != 'cli')
        {
            return Http\HttpRequest::getFromServer();
        }
        else
        {
            return Http\HttpRequest::getEmpty();
        }        
    }

    public function run()
    {
        if(!\defined('APPLICATION_PATH'))
        {
            throw new Exception('APPLICATION_PATH constant is not defined.');
        }

        $this->config = new Config\IniConfig(APPLICATION_PATH . '/config/config.ini');
        $this->config->setEnvironment(\APPLICATION_ENV);

        Module\Module::$modulesPath = APPLICATION_PATH . '/module';

        $httpContext = $this->getHttpContext();

        
        $this->runBootstrap();        
    }

    private function runBootstrap()
    {
        $bootstrapClass = 'Lightnote\Bootstrap';
        $bootstrapFile = APPLICATION_PATH . '/Bootstrap.php';
        if(file_exists($bootstrapFile))
        {
            include_once $bootstrapFile;
            if(class_exists('\Bootstrap'))
            {
                $bootstrapClass = '\Bootstrap';
            }
        }

        $this->bootstrap = new $bootstrapClass($this);
        $this->bootstrap->run();

        $routes = $this->bootstrap->routes;
        $url = $this->httpContext->request->server['REQUEST_URI'];
        $route = $routes->findMatching($url);
        if($route == null)
        {
            throw new Exception('No matching route found.');
        }

        $routeData = $route->getRouteData($url);

        $namespaces = $route->namespaces;
        $controller = $routeData['controller'];
        $action = $routeData['action'];

        foreach($namespaces as $namespace)
        {
            $controllerClass = $namespace . '\\' . $controller . 'Controller';
            if(class_exists($controllerClass))
            {
                $controller = new $controllerClass();
                
            }
        }

    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }    
}