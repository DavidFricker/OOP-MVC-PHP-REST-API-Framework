<?php
class Request
{
    private $url_elements = array();
    private $method;
    private $parameters;

    public function __construct()
    {
        $this->set_method($_SERVER['REQUEST_METHOD']);

        if(isset($_SERVER['PATH_INFO']))
        {
            $this->set_url_elements(explode('/', trim($_SERVER['PATH_INFO'], '/')));
        }
        
        switch($this->get_method())
        {
            case 'GET':
                $this->set_parameters($_GET);
                break;
                
            case 'POST':
            case 'PUT':       
            case 'DELETE':
                $decoded_parameters = Json::decode(file_get_contents('php://input'), true);
                if($decoded_parameters === false)
                {
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
        if($index == -1)
        {
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