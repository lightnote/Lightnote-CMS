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
 * Session class
 */
class Session
{
    private static $nativeSession = null;

    public static $isCookieLess = false;

    private static $isStarted = false;
    private static function start()
    {
        if(self::$isStarted)
        {
            return;
        }
        
        if(self::$isCookieLess)
        {
            ini_set('session.use_cookies', 0);
            ini_set('session.use_trans_sid', 1);
        }

        session_start();
        self::$isStarted = true;
    }


    /**
     *
     * @var string
     */
    private $namespace = '';


    /**
     *
     * @param string $namespace
     */
    public function __construct($namespace = 'Default')
    {
        self::start();

        if(isset($_SESSION))
        {
            $_SESSION['__LIGHTNOTE__'] = array();
            self::$nativeSession[$namespace] = &$_SESSION['__LIGHTNOTE__'];
        }
        else
        {
            // CLI mode
            self::$nativeSession[$namespace] = array();
        }

        $this->namespace = $namespace;
    }

    public function __get($key)
    {
        return self::$nativeSession[$this->namespace];
    }

    public function __set($key, $value)
    {
        self::$nativeSession[$this->namespace] = $value;
    }
}