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

namespace Lightnote\Test;

/**
 * Factory class
 */
class Factory
{
    public static function getHttpRequest($requestCfg)
    {
        $server = array(
          'REDIRECT_STATUS' => '200',
          'HTTP_HOST' => 'lightnote.local',
          'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; de; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10',
          'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
          'HTTP_ACCEPT_LANGUAGE' => 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3',
          'HTTP_ACCEPT_ENCODING' => 'gzip,deflate',
          'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
          'HTTP_CACHE_CONTROL' => 'max-age=0',
          'SERVER_SIGNATURE' => '<address>Apache/2.2.8 (Win32) PHP/5.3.3 Server at lightnote.local Port 80</address>',
          'SERVER_SOFTWARE' => 'Apache/2.2.8 (Win32) PHP/5.3.3',
          'SERVER_NAME' => 'lightnote.local',
          'SERVER_ADDR' => '127.0.0.1',
          'SERVER_PORT' => '80',
          'REMOTE_ADDR' => '127.0.0.1',
          'DOCUMENT_ROOT' => 'E:/Development/Sites/cms2/src/htdocs',
          'SERVER_ADMIN' => 'dmitry.monin@lightnote.net',
          'SCRIPT_FILENAME' => 'E:/Development/Sites/cms2/src/htdocs/index.php',
          'REMOTE_PORT' => '49877',
          'REDIRECT_URL' => '/backend',
          'GATEWAY_INTERFACE' => 'CGI/1.1',
          'SERVER_PROTOCOL' => 'HTTP/1.1',
          'REQUEST_METHOD' => 'GET',
          'QUERY_STRING' => '',
          'REQUEST_URI' => '/backend',
          'SCRIPT_NAME' => '/index.php',
          'PHP_SELF' => '/index.php',
          'REQUEST_TIME' => time(),
          'argv' => array (
          ),
          'argc' => 0,
            
        );

        $serverMapping = array(
            'ip' => 'REMOTE_ADDR',
            'url' => 'REQUEST_URI'
        );

        if(isset($requestCfg['server']) && is_array($requestCfg['server']))
        {
            foreach($requestCfg['server'] as $key=>$value)
            {
                if(\array_key_exists($key, $serverMapping))
                {
                    $server[$serverMapping[$key]] = $value;
                }
                else
                {
                    $server[$key] = $value;
                }
            }
        }

        return new \Lightnote\Http\HttpRequest($requestCfg);
    }

    /**
     *
     * @param array $requestCfg
     * @param array $sessionVars
     * @param array $cacheVars
     * @return \Lightnote\Http\HttpContext
     */
    public static function getHttpContext($requestCfg = array(), $sessionVars = array(), $cacheVars = array())
    {
        $request = self::getHttpRequest($requestCfg);
        $httpContext = new \Lightnote\Http\HttpContext();
        $httpContext->request = $request;
        $httpContext->session = new \Lightnote\Session();
        $httpContext->cache = new \Lightnote\Cache();
        return $httpContext;
    }

}