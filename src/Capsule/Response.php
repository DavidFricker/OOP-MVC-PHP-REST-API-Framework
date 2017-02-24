<?php

namespace DavidFricker\RestAPI\Capsule;

use DavidFricker\RestAPI\Router;

class Response 
{
    private $payload = [];
    private $preset_code = Router::INTERNAL_ERROR;
    
    function __construct($preset_code)
    {
        $this->preset_code = $preset_code;
    }

    // return the object to enable chaining
    public function payload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    public function message($message) {
        $this->payload = array_merge($this->payload, ['message' => $message]);
        return $this;
    }

    private function send_header()
    {
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

            default:
            case Router::INTERNAL_ERROR:
                header('HTTP/1.1 500 Internal Server Error');
                break;
        }
    }

    private function render_json()
    {
        // should we check if the payload is empty or not first?
        header('Content-Type: application/json');
        echo json_encode($this->payload);
    }

    public function render()
    {
        if(headers_sent()) {
            die();
        }

        $this->send_header();
        $this->render_json();
        
        die();
    }
}