<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DavidFricker\RestAPI\Capsule\Response;
use DavidFricker\RestAPI\Router;

/**
 * @covers Response
 */
final class ResponseTest extends TestCase
{
    
    /**
     * @expectedException ArgumentCountError
     */
    public function testConstructorException()
    {
        new Response();
    }

    /**
     * Due to header output clashes between PHPUnit and the Response 
     * object this test must be run in a separate process
     * 
     * @runInSeparateProcess
     */
    public function testResponseOutputPayload()
    {
        $this->expectOutputString('{"phpunit":"keyvalue"}');

        $Response = new Response(Router::CMD_PROCESSED);
        $Response->payload(['phpunit' => 'keyvalue']);
        $Response->render();
    }

    /**
     * Due to header output clashes between PHPUnit and the Response 
     * object this test must be run in a separate process
     * 
     * @runInSeparateProcess
     */
    public function testResponseOutputMessage()
    {
        $this->expectOutputString('{"message":"Hello World"}');

        $Response = new Response(Router::CMD_PROCESSED);
        $Response->message('Hello World');
        $Response->render();
    }

    /**
     * Due to header output clashes between PHPUnit and the Response 
     * object this test must be run in a separate process
     * 
     * @runInSeparateProcess
     */
    public function testResponseOutputPayloadAndMessage()
    {
        $this->expectOutputString('{"message":"Hello World","phpunit":"keyvalue"}');

        $Response = new Response(Router::CMD_PROCESSED);
        
        $Response->message('Hello World');
        $Response->payload(['phpunit' => 'keyvalue']);
        
        $Response->render();

        /*var_dump(headers_list());
        $this->assertEquals(
            200,
           headers_list()
        );*/
    }


    
    /*public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Email::class,
            Email::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            'user@example.com',
            Email::fromString('user@example.com')
        );
    }*/
}
