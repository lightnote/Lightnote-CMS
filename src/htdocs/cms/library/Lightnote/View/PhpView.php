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

namespace Lightnote\View;

/**
 * PhpView class
 */
class PhpView implements \Lightnote\IView
{
    private $data = array();
    private $file = null;


    public function __construct($file)
    {
        $this->file = $file;
    }

    public function assign($key, $value = null)
    {
        if(is_array($key))
        {
            foreach($key as $k=>$v)
            {
                $this->data[$k] = $v;
            }
        }
        else
        {
            $this->data[$key] = $value;
        }
    }

    public function clear($key = null)
    {
        if($key)
        {
            unset($this->data[$key]);
        }
        else
        {
            $this->data = array();
        }

    }

    public function fetch()
    {
        $viewData = $this->data;
        ob_start();
        include_once $this->file;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
}