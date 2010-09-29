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

namespace Lightnote\Routing;

/**
 * Route class
 *
 * @property-read $namespaces
 */
class Route extends \Lightnote\Attribute implements IRoute
{
    const GROUP_REGEXP = '/\{([a-z0-9_-]+)\}/i';
    const CATCH_ALL_REGEXP = '/\{\*([a-z0-9_-]+)\}$/i';

    /**
     *
     * @var string
     */
    private $url = '';

    /**
     *
     * @var array
     */
    private $data = array();

    /**
     *
     * @var array
     */
    private $constrains = array();

    /**
     *
     * @var IRouteHandler
     */
    private $routeHandler = null;

    /**
     *
     * @var array
     */
    private $dataKeys = array();

    /**
     *
     * @var array
     */
    private $namespaces = array();

    /**
     *
     * @var string
     */
    private $lastMatchedUrl = null;


    // @todo implement routeExistingFiles
    // @todo exception: controller, action must be specified

    /**
     *
     * @param string $url Url pattern, i.e. {controller}/{action}/{id}
     * @param Lightnote\Routing\IRouteHandler $routeHandler
     * @param RouteConfig $config
     */
    public function __construct($url, IRouteHandler $routeHandler, RouteConfig $config = null)
    {
        $this->url = $url;
        $this->validateUrlFormat();

        $this->routeHandler = $routeHandler;
        if($config != null)
        {
            $this->namespaces = $config->namespaces;
            $this->data = $config->params;
            $this->constrains = $config->constrains;
        }
        
    }

    private function getCatchAllGroupName()
    {
        if(preg_match(self::CATCH_ALL_REGEXP, $this->url, $matches))
        {
            return $matches[1];
        }

        return null;
    }

    private function getDataKeys()
    {
        if($this->dataKeys != null)
        {
            return $this->dataKeys;
        }

        \preg_match_all(self::GROUP_REGEXP, $this->url, $groupMatches);
        $this->dataKeys = $groupMatches[1];

        return $this->dataKeys;
    }

    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     *
     * @param bool $url
     */
    public function match($url)
    {
        $urlParts = \explode('/', \trim($url, '/'));
        
        $patternParts = \explode('/', \trim($this->url, '/'));

        $catchAllGroupName = $this->getCatchAllGroupName();
        if($catchAllGroupName != null)
        {
            \array_pop($patternParts);            
        }
        else if(\count($patternParts) < \count($urlParts))
        {
            return false;
        }


        $lastIndex = 0;
        for ($i = 0, $count = \count($patternParts); $i < $count; $i++)
        {
            $part = $patternParts[$i];
            $regexp = '/' . \preg_replace(self::GROUP_REGEXP, '(?P<$1>[^\/]+)', $part) . '/i';
            
            $urlPartExists = \array_key_exists($i, $urlParts);

            $lastIndex = $i;
            if($urlPartExists && \preg_match($regexp, $urlParts[$i], $matches))
            {
                foreach($this->getDataKeys() as $groupName)
                {
                    if(\array_key_exists($groupName, $matches))
                    {
                        $this->data[$groupName] = $matches[$groupName];
                    }
                }
            }
            else if (!$urlPartExists)
            {                
                break;
            }
        }

        if($catchAllGroupName !== null)
        {
            $this->data[$catchAllGroupName] = implode('/', \array_splice($urlParts, $lastIndex + 1));
        }

        $result = $this->validateParams();
        
        if($result)
        {
            $this->lastMatchedUrl = $url;
        }

        return $result;
    }

    /**
     *
     * @return RouteData
     */
    public function getRouteData($url)
    {
        if($this->lastMatchedUrl == $url || $this->match($url))
        {
            \uksort($this->data, array($this, 'sortKeys'));
            return new RouteData($this, $this->data);
        }

        return null;
    }

    private function sortKeys($keyA, $keyB)
    {
        $indexA = \array_search($keyA, $this->getDataKeys());
        $indexB = \array_search($keyB, $this->getDataKeys());
        return $indexA > $indexB ? 1 : -1;
    }

    private function validateParams()
    {
        foreach($this->getDataKeys() as $key)
        {
            if(!\array_key_exists($key, $this->data))
            {
                return false;
            }
        }

        /* @var $constrain Constrain\IConstrain */
        foreach($this->constrains as $constrain)
        {
            if(!$constrain->match($this->routeHandler->getHttpContext(), $this, $this->data))
            {
                return false;
            }
        }

        return true;
    }

    private function validateUrlFormat()
    {
        if(preg_match('/^(~|\/)/', $this->url))
        {
            throw new \Lightnote\Exception\System\ArgumentException('The url can not start with ~ or /');
        }

        if(strstr($this->url, '?'))
        {
            throw new \Lightnote\Exception\System\ArgumentException('The url can not contain ?');
        }

        if(preg_match('/{\*([a-z0-9_-]+)\}.+/', $this->url))
        {
            throw new \Lightnote\Exception\System\ArgumentException('The url can not contain ?');
        }
    }

}