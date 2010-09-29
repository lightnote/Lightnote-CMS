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

    /**
     *
     * @param Config $config 
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     *
     * @param array $namespaces
     * @param string $controllerName
     * @param Routing\RouteData $routeData
     * @return bool
     */
    private function executeController($namespaces, $controllerName, $routeData)
    {        
        $requestContext = new Routing\RequestContext($this->getHttpContext(), $routeData);

        foreach($namespaces as $namespace)
        {
            $controllerClass = $namespace . '\\' . $controllerName . 'Controller';

            if(class_exists($controllerClass))
            {
                $controller = new $controllerClass();
                $controller->execute($requestContext);
                return true;
            }
        }

        return false;
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

    /**
     *
     * @return Routing\Route
     */
    private function getRoute($url)
    {
        $routes = $this->bootstrap->routes;
        $route = $routes->findMatching($url);
        if($route == null)
        {
            throw new Exception('No matching route found.');
        }
        return $route;
    }

    public function run()
    {
        if(!\defined('APPLICATION_PATH'))
        {
            throw new Exception('APPLICATION_PATH constant is not defined.');
        }

        Loader::$modulesPath = APPLICATION_PATH . '/module';

        $this->runBootstrap();

        // Getting route
        $url = $this->httpContext->request->server['REQUEST_URI'];
        
        $route = $this->getRoute($url);
        $routeData = $route->getRouteData($url);

        $namespaces = $route->namespaces;
        $controllerName = $routeData['controller'];
        if(empty($controllerName))
        {
            throw new Exception('Route must have a \'controller\' property.');
        }
        
        if(!$this->executeController($namespaces, $controllerName, $routeData))
        {
            throw new Exception('Controller ' . $controllerName . ' not found.');
        }
    }

    private function runBootstrap()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 'on');

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
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }    
}