<?php namespace App\Lemon\Dailian\Contracts;

interface Platform {

	function publish($order);

	function checkAccess();

	function checkPayword();
}