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
 * @author Monin Dmitry <dmitry.monin [at] lightnote [dot] org> on 19.09.2010
 */
class Route implements IRoute
{
    private $name = '';
    private $pattern = '';
    private $defaults = null;
    private $constrains = array();

    public function __construct($name, $pattern, $defaults = null, $constrains = null)
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->defaults = $defaults;
        $this->constrains = $contrains;
    }

    /*private function getDefaultsRegexp($pattern)
    {
        $pattern = \preg_replace('/\{\*?(' . implode('|', array_keys($this->defaults)) . ')\}/', $replacement, $pattern);
        $pattern = \preg_replace('/\/+/', '/', $pattern);

        return $this->getRegexp($pattern);
    }*/

    private function getRegExp($pattern)
    {
        $parts = explode('/', $pattern);

        $regexp = '/';
        $openBraceCount = 0;
        foreach($parts as $part)
        {
            if(preg_match_all('/\{([a-z0-9]+)\}/', $part, $matches))
            {
                $groupNames = $matches[0];
                
                $hasDefault = array_key_exists($groupName, $this->defaults);

                // beginning may exists (?) regexp group
                if($hasDefault)
                {
                    $regexp .= '(';
                }

                // adding group
                $regexp .= '(?P<' . $groupName . '>[^\/]+)';                
            }
        }

        for($i = 0; $i < $openBraceCount; $i++)
        {
            $regexp .= ')?';
        }

        /*$pattern = \preg_replace('/\{([a-z0-9]+)\}/', '(?<$1>[^\/]+)', $pattern);
        $pattern = \preg_replace('/\{\*([a-z0-9]+)\}/', '(?<$1>.*)', $pattern);
        $pattern = '^/' . \trim($pattern, '/') . '$';

        return $pattern;*/

        /*preg_match_all('/[^\/]+/i', $this->pattern, $matches);

        $completePartPattern = '/{\*([a-z0-9_-]+)}/i';
        $partPattern = '/{([a-z0-9_-]+)}/i';


        $regexpParts = array();
        $parts = $matches[0];
        for($i = 0, $count = count($parts); $i < $count; $i++)
        {
            if(preg_match($completePartPattern, $parts[$i], $matches))
            {
                $regexpParts[] = '(?P<' . $matches[1] . '>[^\/]+)';
                break;
            }
            else if(preg_match($partPattern, $parts[$i], $matches))
            {
                $regexpParts[] = '(?P<' . $matches[1] . '>[^\/]+)';
            }
            else
            {
                $regexpParts[] = '[^\/]+';
            }
        }

        return sprintf('/%s/', implode('\/', $regexpParts));*/
    }

    /**
     *
     * @param bool $url
     */
    public function match($url)
    {
        $regexp = $this->getRegExp($this->pattern);
        echo $url . "\n";
        echo $regexp . "\n";
        //echo $this->getDefaultsRegexp($this->pattern) . "\n";

        return true;
        //preg_match($regexp, $url, $matches);
        //print_r($matches);
        //echo "\n\n";
    }

    public function getParams()
    {
        
    }

}