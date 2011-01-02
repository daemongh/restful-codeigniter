RESTful-CodeIgniter
===================
AUTHOR: ZHENJiNG LiANG (liangzhenjing_N_O_S_P_A_M@gmail.com)

LINK:   http://liang.eu/web-dev/php/restful-style-url-in-codeigniter

Introduction
============
RESTful-CodeIgniter is a RESful style url router library for [CodeIgniter](http://codeigniter.com/) PHP Framework.

With this library's help, you can easily map a controller as a "Resource" (YES, like Rails), and then this controller can handles RESTful-style urls. See code below:

    // GET /users
    function index()
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users";
    }

    // GET /users/12345
    function show($id)
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}";
    }

    // GET /users/new
    // I have to use new_form as method name because "new" is a keyword in php
    function new_form()
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/new";
    }

    // GET /users/12345/edit
    function edit($id)
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}/edit";
    }

    // POST /users
    function create()
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users";
    }

    // PUT /users/12345
    function update($id)
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}";
    }

    // DELETE /users/12345
    function delete($id)
    {
        echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}";
    }    
    ...


Sounds great, How can I install && use it?
==========================================

This library is designed to be easily install and easily configure.

Installation:
-------------

All you need to do is drop MY_Router.php into you appliation's libraries directory("system/application/libraries" by default) and everything is done!

Configuration:
--------------

Open application/routers.php, add this line in the bottom, and you are done!

    map_resources('users');

Now you can test these urls by curl:

    $ curl http://192.168.1.99/restful-codeigniter/users//users
    $ curl http://192.168.1.99/restful-codeigniter/users//users/new
    $ curl http://192.168.1.99/restful-codeigniter/users//users/9999
    $ curl http://192.168.1.99/restful-codeigniter/users//users/9999/edit
    $ curl -X PUT http://192.168.1.99/restful-codeigniter/users//users/9999
    $ curl -X POST http://192.168.1.99/restful-codeigniter/users//users/9999
    $ curl -X DELETE http://192.168.1.99/restful-codeigniter/users//users/9999

I need more...
==============
You can register a custom handler in routes.php:

    // custom action handles GET request
    map_resources('GET', 'users/(:id)/custom_action', 'users/custom_action/$1'); 
    
    // some other custom action mappings...
    // custom action handles PUT request
    //map_resources('PUT', 'users/(:id)/custom_action', 'sync_sessions/custom_action/$1');

    // custom action handles all requests (GET, POST, PUT, DELETE)
    //map_resources('users/(:id)/custom_action', 'sync_sessions/custom_action/$1');

LICENSE
=======
This library is released under MIT license.
