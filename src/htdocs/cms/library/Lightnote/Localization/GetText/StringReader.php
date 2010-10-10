<?php

namespace Lightnote\Localization\GetText;

class StringReader {
  var $_pos;
  var $_str;

  function __construct($str='') {
    $this->_str = $str;
    $this->_pos = 0;
    // If string functions are overloaded, we need to use the mb versions
    $this->is_overloaded = ((ini_get("mbstring.func_overload") & 2) != 0) && function_exists('mb_substr');
  }

  function _substr($string, $start, $length) {
    if ($this->is_overloaded) {
        return mb_substr($string,$start,$length,'ascii');
    } else {
        return substr($string,$start,$length);
    }
  }

  function _strlen($string) {
    if ($this->is_overloaded) {
        return mb_strlen($string,'ascii');
    } else {
        return strlen($string);
    }
  }

  function read($bytes) {
      $data = $this->_substr($this->_str, $this->_pos, $bytes);
    $this->_pos += $bytes;
    if ($this->_strlen($this->_str)<$this->_pos)
      $this->_pos = $this->_strlen($this->_str);

    return $data;
  }

  function seekto($pos) {
    $this->_pos = $pos;
    if ($this->_strlen($this->_str)<$this->_pos)
      $this->_pos = $this->_strlen($this->_str);
    return $this->_pos;
  }

  function currentpos() {
    return $this->_pos;
  }

  function length() {
    return $this->_strlen($this->_str);
  }
}

