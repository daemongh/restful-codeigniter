<?php
class Users extends Controller {

	function Users()
	{
		parent::Controller();	
	}

    // GET /users
	function index()
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users\n";
	}

    // GET /users/12345
	function show($id)
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}\n";
	}

    // GET /users/new
	function new_form()
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/new\n";
	}

    // GET /users/12345/edit
	function edit($id)
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}/edit\n";
	}

    // POST /users
	function create()
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users\n";
	}

    // PUT /users/12345
	function update($id)
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}\n";
	}

    // DELETE /users/12345
	function delete($id)
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}\n";
	}

    // GET /users/12345/custom_action
	function custom_action($id)
	{
		echo __METHOD__ . "<br>\n" . $_SERVER['REQUEST_METHOD'] . " /users/{$id}/custom_action\n";
	}
}
