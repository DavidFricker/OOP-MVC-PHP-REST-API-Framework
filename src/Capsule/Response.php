<?php

namespace DavidFricker\RestAPI\Capsule;

use DavidFricker\RestAPI\Router;

/**
 * 
 */
class Response 
{
    private $payload;
    private $preset_code = Router::INTERNAL_ERROR;
    const RATE_LIMITED = 429;
    
    function __construct($preset_code)
    {   
        if ($preset_code === null) {
            throw new \ArgumentCountError ('Present response code not supplied');
        }

        $this->preset_code = $preset_code;
        $this->payload = [];
    }

    // return the object to enable chaining
    public function payload($payload)
    {
        $this->payload =  array_merge($this->payload, $payload);
        return $this;
    }

    public function message($message) {
        $this->payload = array_merge($this->payload, ['message' => $message]);
        return $this;
    }

    private function send_header()
    {
        if(headers_sent()) {
            return;
        }

        switch($this->preset_code)
        {
            case Router::CMD_PROCESSED:
                header('HTTP/1.1 200 OK');
                break;

            case Router::CMD_UNPROCESSABLE:
                header('HTTP/1.1 422 Unprocessable Entity');
                break;

            case Router::CMD_UNKNOWN:
                header('HTTP/1.1 400 Bad Request');
                break;

            case Router::CMD_INVALID:
                header('HTTP/1.1 405 Method not allowed');
                break;

            case Router::CMD_MALFORMED:
                header('HTTP/1.1 409 Conflict');
                break;

            case Router::USR_UNAUTHORIZED:
                header('HTTP/1.1 401 Unauthorized');
                break;

            case Router::SRC_NOTFOUND:
                header('HTTP/1.1 404 Not Found');
                break;

            case self::RATE_LIMITED:
                header('HTTP/1.1 429 Too Many Requests');
                break;

            default:
            case Router::INTERNAL_ERROR:
                header('HTTP/1.1 500 Internal Server Error');
                break;
        }
    }

    private function render_json()
    {
        if(!headers_sent()) {
            header('Content-Type: application/json');
        }

        // should we check if the payload is empty or not first?
        echo json_encode($this->payload);
    }

    public function render()
    {
        $this->send_header();
        $this->render_json();
    }
}