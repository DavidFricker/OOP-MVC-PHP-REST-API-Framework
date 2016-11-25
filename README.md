# Simple OOP MVC PHP REST API Framework
That's a lot of acronyms.

NB: This is a draft document

There is nothing especially ground breaking about this API Framework. It was born from my second year computer science project and has evolved and been re-thought into what you see before you today.

It's key selling point is how lightweight and simple to use, extend and understand it is.

## How does it work?
Put simply, the framework will route requests to the correct controller and model. It will do this by analysing request URI for the controller name and the request type (be it POST, PUT, GET, etc.). It will then do some sanity checks, before initialising a new controller and model object and calling the correct method on the controller.

### How do I add an end point?
You must create a new controller, in the `controllers` folder. This new controller must extend `AbstractController`. In addition, you must also create a corresponding `model` and place it in the `models` folder. The new model must extend `AbstractController`.

### How do I add resource?
Let us take the example of an API that exposes the account email address for update. We would expect it to be formatted in the following way, PUT `/accounts/email`. 
To create this one must first create an accounts controller and model. the controller must then declare a method in the following format 
`public function put_email(){}`. inside this method the developer can then access the `request` object from the controller object itself `$this->request->get_parameter('new_address');`.
Since this endpoint will likely (hopefully) require authentication a call to the member function `is_authorised` should be performed.

### Important info
Included is a .htaccess file. It simply redirects all requests to the index file. if you are not running apache as your webserver you will need to redirect all traffic to `/index.php/$1` where $1 is the rest of the URI.
