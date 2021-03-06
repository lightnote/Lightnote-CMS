<?php

namespace Lightnote\Config;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../../../../src/htdocs/cms/library/Lightnote/Config/IniConfig.php';

/**
 * Test class for IniConfig.
 * Generated by PHPUnit on 2010-10-02 at 05:58:09.
 */
class IniConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var IniConfig
     */
    protected $iniConfig;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->iniConfig = new IniConfig(DATA_PATH . '/config.ini');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    public function testObjectProperty()
    {
        $this->iniConfig->setEnvironment('production');
        $value = $this->iniConfig->getProperty('lightnote');
        $this->assertEquals($value->repository->config->password, 'test1');

        $this->iniConfig->setEnvironment('local');
        $value = $this->iniConfig->getProperty('lightnote');
        $this->assertEquals($value->repository->config->password, 'test');

        $value = $this->iniConfig->getProperty('nothing');
        $this->assertEquals($value, null);
    }

    public function testStringProperty()
    {
        $this->iniConfig->setEnvironment('production');
        $value = $this->iniConfig->getProperty('lightnote.repository.config.password');
        $this->assertEquals($value, 'test1');
    }

    public function testArrayProperty()
    {
        $this->iniConfig->setEnvironment('production');
        $value = $this->iniConfig->getProperty('lightnote.someArray');
        $this->assertTrue(is_array($value));
        $this->assertTrue(count($value) == 2);
        $this->assertEquals($value[1], 'def');
    }

    public function testSetProperty()
    {
        $this->iniConfig->setProperty('my.property', 'test');
        $this->assertEquals($this->iniConfig->getProperty('my.property'), 'test');

        $this->iniConfig->setEnvironment('local');
        $this->assertEquals($this->iniConfig->getProperty('my.property'), null);
    }

}

?>
