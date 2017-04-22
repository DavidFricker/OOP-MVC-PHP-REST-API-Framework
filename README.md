# PHP REST API Framework
This REST API framework is designed to be very lightweight to work well in high request volume deployments.


## Usage example 
A working example can be found in the `example` directory.

## How does it work?
The package will automatically route requests to the correct controller, model, and member method without the need to explicitly declare end-points. This automation is performed by analysing the HTTP request and URI for the controller and model name and the member method name (this point is illustrated better in the example folder).

### Creating an end point
You must create a new controller class and a corresponding `model` and place it in the `models` folder. 

### Creating an add resource
The following example will show how to add a new resource to the API that exposes the account email address for update. This resource would be accessed in the following way, `PUT` `/accounts/email`. 

First the controller and model classes must be created. The controller must then contain a method in the following format 
`public function put_email()`. The `put_email()` method will be called when a `PUT` request is used to access `/accounts/email`. 

### Server configuration
Included is a `.htaccess` file (for Apache servers). This file redirects all HTTP requests that do not lead to a file to the index file.
