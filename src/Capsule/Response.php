<?php

namespace DavidFricker\RestAPI\Capsule;

use DavidFricker\RestAPI\Router;

/**
 * Represents a HTTP response, header and body
 */
class Response 
{   
    /**
     * Additional informaiton to be sent to the client
     * @var array
     */
    private $payload;

    /**
     * Default response code, 500
     * @var integer
     */
    private $preset_code = Router::INTERNAL_ERROR;

    /**
     * HTTP response code constants
     */
    const RATE_LIMITED = 429;
    
    /**
     * @throws ArgumentCountError
     * @param integer $preset_code HTTP response code
     */
    function __construct($preset_code)
    {   
        if ($preset_code === null) {
            throw new \ArgumentCountError ('Present response code not supplied');
        }

        $this->preset_code = $preset_code;
        $this->payload = [];
    }

    /**
     * Set a extra data to be sent to the client
     * @param  array $payload additional, serialisiable, data
     * @return Response $this
     */
    public function payload($payload)
    {
        $this->payload = array_merge($this->payload, $payload);
        return $this;
    }

    /**
     * Set a plain text status message to be sent to the client 
     * 
     * @param  string $message plain text message describing the response status
     * @return Response $this
     */
    public function message($message) {
        $this->payload = array_merge($this->payload, ['message' => $message]);
        return $this;
    }

    /**
     * Send HTTP response code to client according to the selected response code constant
     * 
     * @return void
     */
    private function send_header()
    {
        if (headers_sent()) {
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

    /**
     * Emit json content type header and dump the json string representation of the payload to the ouput stream
     * 
     * @return void
     */
    private function render_json()
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        echo json_encode($this->payload);
    }

    /**
     * Sends the HTTP response headers and payload to the client
     * 
     * @return void
     */
    public function render()
    {
        $this->send_header();
        $this->render_json();
    }
}
