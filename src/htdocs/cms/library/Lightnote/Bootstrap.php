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
 * Bootstrap class
 */
abstract class Bootstrap
{
    /**
     *
     * @var Application
     */
    protected $application;


    /**
     *
     * @var Routing\RouteCollection
     */
    public $routes;

    /**
     *
     * @var Module\ModuleCollection
     */
    public $modules;
    
    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->routes = new Routing\RouteCollection();
        $this->modules = new Module\ModuleCollection();
    }

    protected function registerModules()
    {
        $handler = new Mvc\RouteHandler($this->application->httpContext);
        $backendModule = new Module\BackendModule(
            'Lightnote\Backend',
            $this->application->config->getProperty('lightnote.module.backend')
        );
        $backendModule->setupRoutes($this->routes, $handler);

        $this->modules[] = $backendModule;        
    }

    protected function registerRoutes()
    {        
        $handler = new Mvc\RouteHandler($this->application->httpContext);
    }    

    public function run()
    {
        $this->registerModules();
        $this->registerRoutes();
    }
}