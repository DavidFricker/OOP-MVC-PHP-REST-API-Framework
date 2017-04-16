<?php

namespace DavidFricker\RestAPI\Capsule;

use DavidFricker\CleanJson\CleanJson;

/**
  * A representation of a HTTP request
  *
  * Stores and allows easy access to common important variables of a HTTP request.
  */
class Request {
    /**
     * url parts stored in an array e.g. example.com/path/to/resource becomes ['path','to','resource']
     * @var array
     */
    private $url_elements;

    /**
     * Prased input from $_POST, $_GET, or php://input
     * @var array
     */
    private $request_parameters;

    /**
     * HTTP Request method
     * @var string
     */
    private $http_method;
    
    /**
     * Gathers HTTP request information
     */
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];

        if (isset($_SERVER['PATH_INFO'])) {
            $this->url_elements = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        }
        
        switch($this->getMethod()) {
            case 'GET':
                $this->request_parameters = $_GET;
                break;
                
            case 'POST':
                $this->request_parameters = $_POST;

            case 'PUT':       
            case 'DELETE':
                $decoded_parameters = CleanJson::decode(file_get_contents('php://input'), true);
                if ($decoded_parameters != false) {
                    $this->request_parameters = array_merge($this->request_parameters, $decoded_parameters);
                }
                break;

            default:
                throw new InvalidHTTPMethodException('HTTP method not supported');
                break;
        }
    }

    /**
     * Getter method for the HTTP request method
     * 
     * @return string HTTP request method 
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Fetch parts of the URL/path
     *
     * Indexed from zero. The path elements are split around '/'.
     * @example path /path/to/page, getUrlElements(1) would return the string 'to'
     * 
     * @param  integer $index index of the array of path elements 
     * @return string         path element
     */
    public function getUrlElements($index=-1) {
        if ($index == -1) {
            return $this->url_elements;
        }
        
        if (count($this->url_elements) > $index) {
            return $this->url_elements[$index];
        }

        return false;        
    }

    /**
     * Fetch parameters sent with the request
     *
     * Acts similarly to the $_REQUEST array.
     * 
     * @param  string $index name of the variable you would like the value for
     * @return string        value found at the indexed location, or false
     */
    public function getParameters($index = '') {
        if ($index == '') {
            return $this->request_parameters;
        }
        
        if (isset($this->request_parameters[$index])) {
            return $this->request_parameters[$index];
        }

        return false;
    }

    /**
     * Fetch the name of the function of a controller class to call to fullfill request
     * @return string the name of the class method that should exist for the request to be valid
     */
    public function getMethodName()
    {
        $url_elements = $this->getUrlElements();
        if(count($url_elements) == 1)
        {
            return $this->getMethod();
        }

        unset($url_elements[0]);

        return $this->getMethod().'_'.implode('_', $url_elements);
    }
}






/*



class Request
{
    private $url_elements = array();
    private $method;
    private $parameters;

    public function __construct()
    {
        $this->set_method($_SERVER['REQUEST_METHOD']);

        if (isset($_SERVER['PATH_INFO'])) {
            $this->set_url_elements(explode('/', trim($_SERVER['PATH_INFO'], '/')));
        }
        
        switch($this->get_method()) {
            case 'GET':
                $this->set_parameters($_GET);
                break;
                
            case 'POST':
            case 'PUT':       
            case 'DELETE':
                $decoded_parameters = CleanJson::decode(file_get_contents('php://input'), true);
                if ($decoded_parameters === false) {
                    new Response(CMD_MALFORMED)->render();
                }

                $this->set_parameters($decoded_parameters);
                break;

            default:
                new Response(CMD_INVALID)->render();
                break;
        }
    }

    public function set_method($method)
    {
        // no need for validation since the switch statement in the 
        // constructor will catch anything other than the accepted types
        $this->method = strtoupper($method);
    }

    public function set_url_elements($url_elements)
    {
        $this->url_elements = $url_elements;
    }

    public function get_method()
    {
        return $this->method;
    }

    public function get_url_elements($index=-1)
    {
        if($index == -1) {
            return $this->url_elements;
        }
        
        if(count($this->url_elements) > $index)
        {
            return $this->url_elements[$index];
        }

        return false;        
    }

    public function set_parameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function get_parameters($index = '')
    {
        if($index == '')
        {
            return $this->parameters;
        }
        
        if(isset($this->parameters[$index]))
        {
            return $this->parameters[$index];
        }

        return false;
    }

    // get the name of the function of a controller class to call to fullfill request
    public function get_method_name()
    {
        $url_elements = $this->get_url_elements();
        if(count($url_elements) == 1)
        {
            return $this->get_method();
        }

        unset($url_elements[0]);

        return $this->get_method().'_'.implode('_', $url_elements);
    }
}
*/
