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

    /**
     *
     * @var PhpView
     */
    private $master = null;

    /**
     *
     * @var string
     */
    private $placeholder = array();
    private $content = '';

    public function __construct($file)
    {        
        if(!file_exists($file))
        {
            throw new \Lightnote\Exception\System\FileNotFoundException("File '" . $file . "' couldn't be found");
        }

        $this->file = realpath($file);
    }

    public function assign($key, $value = null)
    {
        if($key instanceof \Lightnote\Http\NameValueCollection)
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


    private function getPlaceholderAttributes($attrStr)
    {
        $attrPattern = '/([^\s=]+)[\s\n\t\r]*=[\s\n\t\r]*[\'"]([^<\'"]*)[\'"]/';
        preg_match_all($attrPattern, $attrStr, $attrMatches, \PREG_SET_ORDER);
        $attributes = array();

        for($i = 0, $count = count($attrMatches); $i < $count; $i++)
        {
            $attributes[$attrMatches[$i][1]] = $attrMatches[$i][2];
        }
        return $attributes;
    }

    public function fetch()
    {
        return $this->fetchFile($this->file);
    }

    private function fetchFile($file)
    {
        $cwd = \getcwd();

        $viewData = $this->data;

        chdir(dirname($this->file));
        ob_start();
        include $this->file;
        $content = ob_get_contents();
        ob_end_clean();
        chdir($cwd);
        
        if($this->master)
        {
            $this->master->content = $content;
            
            $pattern  = '/<view:placeholder([^>]+)>/s';

            if(preg_match_all($pattern, $content, $matches, \PREG_SET_ORDER))
            {
                for($i = 0, $count = count($matches); $i < $count; $i++)
                {
                    $attributes = $this->getPlaceholderAttributes($matches[$i][1]);
                    if(empty($attributes['name']))
                    {
                        throw new \Lightnote\Exception('ViewError (' . $this->file . '): Placeholder omits required attribute "name".');
                    }

                    $offset = \Lightnote\Util\String::indexOf($content, $matches[$i][0]);
                    $end = \Lightnote\Util\String::indexOf($content, '</view:placeholder>', $offset);
                    $start = $offset + \Lightnote\Util\String::length($matches[$i][0]);
                    $placeholderContent = \Lightnote\Util\String::subString($content, $start, $end - $start);
                    
                    $this->master->placeholder[$attributes['name']] = $placeholderContent;                                        
                }                
            }
            
            return $this->master->fetch();
        }

        return $content;
    }

    public function setMaster($fileName)
    {
        if(!file_exists($fileName))
        {
            throw new \Lightnote\Exception\System\FileNotFoundException('Master file "' . $fileName . '" couldn\'t be found (in "' . $this->file . '").');
        }
        $this->master = new PhpView(realpath($fileName));
        
    }
}