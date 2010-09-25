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
 * Config class
 *
 * @author Monin Dmitry <dmitry.monin [at] lightnote [dot] org> on 19.09.2010
 */
class Config
{
    protected $env = 'production';
    protected $data = array();

    public function getProperty($propertyName)
    {
        if(!\array_key_exists($this->env, $this->data))
        {
            throw new \Lightnote\Exception('Environment couldn\'t be found (' . $this->env . ').' );
        }

        if(\array_key_exists($propertyName, $this->data[$this->env]))
        {
            return $this->data[$this->env][$propertyName];
        }

        // trying to fetch an object with property
        $obj = new \stdClass();
        $found = false;
        foreach($this->data[$this->env] as $key=>$value)
        {
            if(strpos($key, $propertyName) === 0)
            {
                $subKey = str_replace($propertyName . '.', '', $key);
                $this->populateObject($obj, $subKey, $value);
                $found = true;
            }
        }

        return $found ? $obj : null;
    }

    private function populateObject($obj, $subKey, $value)
    {
        $subKeyData = explode('.', $subKey);
        $property = \array_shift($subKeyData);

        if(count($subKeyData) > 0)
        {
            if(!property_exists($obj, $property))
            {
                $obj->$property = new \stdClass();
            }
            
            $this->populateObject($obj->$property, implode('.', $subKeyData), $value);            
        }
        else if(preg_match('/\[\]$/', $property))
        {
            if(!property_exists($obj, $property))
            {
                $obj->$property = array();
            }

            $obj->{$property}[] = $value;
        }
        else
        {
            $obj->$property = $value;
        }
    }

    public function setEnvironment($environment)
    {
        $this->env = $environment;
    }

    public function setProperty($propertyName, $propertyValue)
    {
        if(!$this->data[$this->env])
        {
            $this->data[$this->env] = array();
        }
        
        $this->data[$this->env][$propertyName] = $propertyValue;
    }
}