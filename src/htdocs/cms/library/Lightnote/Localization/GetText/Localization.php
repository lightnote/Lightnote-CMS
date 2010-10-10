<?php
namespace Lightnote\Localization\GetText;

class Localization implements \Lightnote\Localization\ILocalization
{
    private $lang;
    
    public function __construct($localeFile)
    {
        $stream = new CachedFileReader($localeFile);
        $this->lang   = new Reader($stream);
    }
    
    public function translate($text)
    {
        return $this->lang->translate($text);;
    }
}
