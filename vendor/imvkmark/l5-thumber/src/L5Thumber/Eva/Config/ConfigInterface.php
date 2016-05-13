<?php namespace Imvkmark\L5Thumber\Eva\Config;

interface ConfigInterface {

	public function setOptions($options);

	public function getOptions();

	public function setOption($option, $value);

	public function getOption($option);

	public function hasOption($option);

	public function toArray();
}
