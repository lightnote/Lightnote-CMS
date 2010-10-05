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

namespace Lightnote\Http;

/**
 * HttpRequest class
 *
 * @property-read $get
 * @property-read $post
 * @property-read $cookies
 * @property-read $server
 * @property-read $files
 * @property-read $method
 */
class HttpRequest
{
    /**
     *
     * @var NameValueCollection
     */
    private $get = null;

    /**
     *
     * @var NameValueCollection
     */
    private $post = null;

    /**
     *
     * @var NameValueCollection
     */
    private $cookies = null;

    /**
     *
     * @var NameValueCollection
     */
    private $server = null;

    /**
     *
     * @var NameValueCollection
     */
    private $files = null;

    /**
     *
     * @var string
     */
    private $method = 'GET';

    /**
     *
     * @return <type> 
     */
    public static function getEmpty()
    {
        $request = new HttpRequest();
        $request->get = new NameValueCollection();
        $request->post = new NameValueCollection();
        $request->server = new NameValueCollection();
        $request->files = new NameValueCollection();
        $request->cookies = new NameValueCollection();

        return $request;
    }

    /**
     * @param $param an associative array with config variables
     * @example array(
     *  'get' => array(),
     *  'post' => array(),
     *  'files' => array()
     * )
     *
     * @return HttpRequest
     */
    public static function getByParams($params)
    {
        $request = new HttpRequest();
        foreach($params as $key=>$value)
        {
            if(\property_exists($key, $this))
            {
                if(is_array($value))
                {
                    $value = new NameValueCollection($value);
                }
                else if($key != 'method' && !($value instanceof NameValueCollection))
                {
                    throw new \Lightnote\Exception("'" . $key . '\' config property should be an instance of NameValueCollection.');
                }

                $this->$key = $value;
            }
        }
    }

    /**
     *
     * @return HttpRequest 
     */
    public static function getFromServer()
    {
        $request = new HttpRequest();
        if(!isset($_GET) || !isset($_POST))
        {
            return $request;
        }

        $request->get = new NameValueCollection($_GET);
        $request->post = new NameValueCollection($_POST);
        $request->server = new NameValueCollection($_SERVER);
        
        
        // @todo implement setting of files
        // @todo implement setting of cookies

        return $request;
    }

    public function __get($key)
    {
        if($this->$key)
        {
            return $this->$key;
        }
    }

    public function __set($key, $value)
    {
        if($this->$key)
        {
            throw new \Lightnote\Exception($key . ' is read-only.', 1100, null);
        }

        $this->$key = $value;
    }
}