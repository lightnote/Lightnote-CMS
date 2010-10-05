<?php
namespace Lightnote\Translation\GetText;

class GetTextTranslation implements \Lightnote\Translation\ITranslation
{
    private $lang;
    
    public function __construct($localeFile)
    {
        $this->domain = $domain;
        
        $stream = new CachedFileReader($localeFile);
        $this->lang   = new Reader($stream);
    }
    
    public function translate($text)
    {
        return $this->lang->translate($text);;
    }
}
