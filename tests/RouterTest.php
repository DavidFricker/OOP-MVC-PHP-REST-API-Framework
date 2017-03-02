<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DavidFricker\RestAPI\Capsule\Response;
use DavidFricker\RestAPI\Capsule\Request;
use DavidFricker\RestAPI\Router;

/**
 * @covers Router
 */
final class RouterTest extends TestCase
{
    const EXAMPLE_NAMESPACE_MODEL = 'DavidFricker\RestAPI\Example\Model\\';
    const EXAMPLE_NAMESPACE_CONTROLLER = 'DavidFricker\RestAPI\Example\Controller\\';
    
    /**
     * @expectedException ArgumentCountError
     */
    public function testConstructorExceptionNoArgs()
    {
        new Router();
    }

    /**
     * @expectedException ArgumentCountError
     */
    public function testConstructorExceptionNoModel()
    {
        new Router(self::EXAMPLE_NAMESPACE_CONTROLLER);
    }

    /**
     * @expectedException ArgumentCountError
     */
    public function testConstructorExceptionNoController()
    {
        new Router(null, self::EXAMPLE_NAMESPACE_MODEL);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testServeWithoutRequest()
    {
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);
        $Router->serve(null);
    }

    public function testServeWithRequestCannotCallBaseGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_MALFORMED))->message('You cannot call the base, please choose an end-point.')
        );
    }

    public function testServeWithRequestCannotCallBasePost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_MALFORMED))->message('You cannot call the base, please choose an end-point.')
        );
    }

    public function testServeWithRequestCannotCallBasePut()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_MALFORMED))->message('You cannot call the base, please choose an end-point.')
        );
    }

    public function testServeWithRequestCannotCallBaseDelete()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_MALFORMED))->message('You cannot call the base, please choose an end-point.')
        );
    }

    public function testServeWithRequestIncorrectEndPoint()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = '/foo';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_UNKNOWN))->message('End-point not found, please refer to the documentation.')
        );
    }

    public function testServeWithRequestCorrectEndPointWrongHttpMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['PATH_INFO'] = '/example';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_INVALID))->message('Operation not possible on this end-point.')
        );
    }

    public function testServeWithRequestCorrectEndPointCorrectHttpMethodNoUsernameOrPass()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['PATH_INFO'] = '/example';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            new Response(Router::USR_UNAUTHORIZED)
        );
    }

    public function testServeWithRequestCorrectEndPointCorrectHttpMethodWithUsernameOrPass()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['PATH_INFO'] = '/example';
        $_SERVER['PHP_AUTH_USER'] = 'root';
        $_SERVER['PHP_AUTH_PW'] = 'toor';

        $Request = new Request();
        $Router = new Router(self::EXAMPLE_NAMESPACE_CONTROLLER, self::EXAMPLE_NAMESPACE_MODEL);

        $Response = $Router->serve($Request);
        $this->assertEquals(
            $Response,
            (new Response(Router::CMD_PROCESSED))->message('bar')
        );
    }

    

  
    /*
     * Due to header output clashes between PHPUnit and the Response 
     * object this test must be run in a separate process
     * 
     * @runInSeparateProcess
     * /
    public function testResponseOutputPayloadAndMessage()
    {
        $this->expectOutputString('{"message":"Hello World","phpunit":"keyvalue"}');

        $Response = new Response(Router::CMD_PROCESSED);
        
        $Response->message('Hello World');
        $Response->payload(['phpunit' => 'keyvalue']);
        
        $Response->render();

    }
*/

}
