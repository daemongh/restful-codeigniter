<?php
/**
 * RESTful-CodeIgniter
 *
 * A RESTful url router library for CodeIgniter
 *
 * @author      ZHENJiNG LiANG (liangzhenjing_N_O_S_P_A_M@gmail.com)
 * @license     MIT license
 * @link        http://liang.eu/web-dev/php/restful-style-url-in-codeigniter
 */

///////////////////////////////////////////////////////////////////////////////
// NOTE: 
// This Library rewrites :id as ([^/]+) to match non-numeric id field,
// Added :uuid to match UUID (aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee)
// :any and :num remain the same
// See code below for details
// You can change at will
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// map_resources()                              --> Return all routes
// map_resources($controller_name)              --> Map default restful routes
// map_resources($method, $pattern, $replace)   --> Add a custom route
///////////////////////////////////////////////////////////////////////////////
function map_resources()
{
    static $routes = array();

    $arg_num = func_num_args();
    if($arg_num == 0)
    {
        return $routes;
    }
    elseif($arg_num == 1)
    {
        // map_resources($controller_name)   --> return all routes
        $controller = func_get_arg(0);
        
        // Generate RESTful url match patterns
        $routes['GET']["{$controller}"]             = "{$controller}/index";
        $routes['GET']["{$controller}/new"]         = "{$controller}/new_form";
        $routes['GET']["{$controller}/(:id)"]       = "{$controller}/show/$1";
        $routes['GET']["{$controller}/(:id)/edit"]  = "{$controller}/edit/$1";

        $routes['POST']["{$controller}"]            = "{$controller}/create";
        $routes['PUT' ]["{$controller}/(:id)"]      = "{$controller}/update/$1";
        $routes['DELETE']["{$controller}/(:id)"]    = "{$controller}/delete/$1";
    }
    elseif($arg_num == 3)
    {
        // Custom url routing
        $args    = func_get_args();
        $method  = $args[0];
        $pattern = $args[1];
        $replace = $args[2];

        $routes[$method][$pattern] = $replace;
    }
}

///////////////////////////////////////////////////////////////////////////
// RESTful style url router
///////////////////////////////////////////////////////////////////////////
class MY_Router extends CI_Router
{
    function MY_Router()
    {
        // HACK: support overridding REQUEST_METHOD by posting a _method parameter
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['_method']))
        {
            $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
        }
        elseif(isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']))
        {
            $_SERVER['REQUEST_METHOD'] = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }
        
        parent::CI_Router();
    }

    // Overrides CI_Router::_parse_routes. 
    // RESTful style url matching
    function _parse_routes()
    {
        $rest_routes = map_resources();

        // we do this for performence
		if (empty($rest_routes) && count($this->routes) == 1)
		{
			$this->_set_request($this->uri->segments);
			return;
		}

        // also this...
		$uri = implode('/', $this->uri->segments);
		if (isset($this->routes[$uri]))
		{
			$this->_set_request(explode('/', $this->routes[$uri]));
			return;
		}

        // RESTful url matching...
        $request_method = $_SERVER['REQUEST_METHOD'];
        $routes = (isset($rest_routes[$request_method]) && $rest_routes[$request_method]) ? $rest_routes[$request_method] : array();
        foreach($routes as $pattern => $replace)
        {
            $pattern = str_replace(':id',   '[^/]+',  $pattern); // use this to match non-numeric id field
            $pattern = str_replace(':any',  '.+',     $pattern);
            $pattern = str_replace(':num',  '[0-9]+', $pattern);
            $pattern = str_replace(':uuid', '[a-zA-Z0-9]{8}(-[a-zA-Z0-9]{4}){3}-[a-zA-Z0-9]{12}', $pattern);

            // Does the RegEx match?
			if (preg_match("#^{$pattern}$#", $uri))
			{
				// Do we have a back-reference?
				if (strpos($replace, '$') !== FALSE && strpos($pattern, '(') !== FALSE)
				{
					$replace = preg_replace("#^{$pattern}$#", $replace, $uri);
				}

                // we are done
				$this->_set_request(explode('/', $replace));
				return;
			}
        }

        // if non of the rules match, then go on...
        parent::_parse_routes();
    }
}
