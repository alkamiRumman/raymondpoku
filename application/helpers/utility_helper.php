<?php

function getSession()
{
	return $_SESSION['user'];
}

function isAdmin()
{
	return $_SESSION['user']->type === 'Admin' ? true : false;
}

function isUser()
{
	return $_SESSION['user']->type === 'User' ? true : false;
}

function dnd($var)
{
	echo '<pre style="border-top: 2px solid red; border-bottom: 2px solid green; margin: 5px 0">';
	var_dump($var);
	echo '</pre>';
}

function login_url($url = '')
{
	return base_url('site/' . $url);
}

function admin_url($url = '')
{
	return base_url('admin/' . $url);
}

function user_url($url = '')
{
	return base_url('user/' . $url);
}

function dnp($var)
{
	echo '<pre style="border-top: 2px solid red; border-bottom: 2px solid green; margin: 5px 0">';
	print_r($var);
	echo '</pre>';
}

function sendJson($data)
{
	header('Content-Type: application/json');
	echo json_encode($data);
}

function currentUserType()
{
	return $_SESSION["user"]->type;
}

