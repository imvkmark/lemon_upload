<?php namespace App\Lemon\Repositories\Contracts;

interface FormType {

	/**
	 * 渲染HTML
	 * @return mixed
	 */
	function render();

	/**
	 * 获取默认值
	 * @return mixed
	 */
	function defaultValue();


	function value();

	function label();

	function tip();

}