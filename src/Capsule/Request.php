<?php

namespace DavidFricker\RestAPI\Capsule;

use DavidFricker\CleanJson\CleanJson;

/**
  * A wrapper around a DB driver to expose a uniform interface
  *
  * Bassically an abstraction over the complexity of the PDO class, but by design this could wrap any strctured storage mechanism 
  * A database engine adapter
  *
  * @param string $myArgument With a *description* of this argument, these may also
  *    span multiple lines.
  *
  * @return void
  *
 *
 */
class Request {
    // url parts stored in an array e.g. example.com/path/to/resource becomes ['path','to','resource']
    private $url_elements;
    private $request_parameters;
    private $http_method;
    
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

    public function getMethod() {
        return $this->method;
    }

    public function getUrlElements($index=-1) {
        if ($index == -1) {
            return $this->url_elements;
        }
        
        if (count($this->url_elements) > $index) {
            return $this->url_elements[$index];
        }

        return false;        
    }

    public function getParameters($index = '') {
        if ($index == '') {
            return $this->request_parameters;
        }
        
        if (isset($this->request_parameters[$index])) {
            return $this->request_parameters[$index];
        }

        return false;
    }

    // get the name of the function of a controller class to call to fullfill request
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