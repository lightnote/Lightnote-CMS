<?php

namespace Lightnote\Routing;

require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__) . '/../../../../src/library/Lightnote/Routing/Route.php';

/**
 * Test class for Route.
 * Generated by PHPUnit on 2010-09-21 at 12:50:17.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @todo Implement testMatch().
     */
    public function testMatch()
    {
        $route = new Route(
            'route1',
            'category/abc-{language}-{country}/{controller}/{action}/{id}',
            array(
                'controller' => 'DefaultController',
                'action' => 'List'
            )
        );

        $this->assertTrue(
            $route->match('category/abc-de-DE')
        );

        /*$this->assertTrue(
            $route->match('category/abc-de-DE/MyController')
        );

        $this->assertTrue(
            $route->match('category/abc-de-DE/MyController/MyAction')
        );*/
    }

}

?>