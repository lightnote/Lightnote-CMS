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

namespace Lightnote\Mvc;

/**
 * @property-read \Lightnote\Http\HttpContext $httpContext
 * @property-read \Lightnote\Routing\RouteData $routeData
 */
class Controller extends \Lightnote\Attribute
{
    /**
     *
     * @var \Lightnote\Http\NameValueCollection
     */
    public $viewData = null;

    /**
     *
     * @var \Lightnote\Routing\RouteData
     */
    protected $routeData = null;

    /**
     *
     * @var \Lightnote\Http\HttpContext
     */
    protected $httpContext;

    public function getRouteData()
    {
        return $this->routeData;
    }

    public function getHttpContext()
    {
        return $this->httpContext;
    }

    public function execute(\Lightnote\Routing\RequestContext $requestContext)
    {
        $this->viewData = new \Lightnote\Http\NameValueCollection();
        $this->routeData = $requestContext->routeData;
        $this->httpContext = $requestContext->httpContext;

        $action = $requestContext->routeData['action'];
        if(empty($action))
        {
            throw new \Lightnote\Exception('Route action can not be empty.');
        }

        $methodName = $action . 'Action';

        if(\method_exists($this, $methodName))
        {
            $dataTokens = $requestContext->routeData->dataTokens;
            unset($dataTokens['controller'], $dataTokens['action']);

            /* @var $actionResult IActionResult */
            $actionResult = \call_user_func_array(array($this, $methodName), $dataTokens);
            if(!($actionResult instanceof IActionResult))
            {
                throw new \Lightnote\Exception('Result of action must be an instance of IActionResult.');
            }

            $controllerContext = new ControllerContext();
            $controllerContext->controller = $this;
            $controllerContext->httpContext = $requestContext->httpContext;
            $controllerContext->routeData = $requestContext->routeData;

            $actionResult->executeResult($controllerContext);
        }
    }

    protected function view()
    {
        return new ViewResult();
    }
    
}
