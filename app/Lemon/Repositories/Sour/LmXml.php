<?php namespace App\Lemon\Repositories\Sour;

/*
	[UCenter] (C)2001-2099 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms
	$Id: xml.class.php 1059 2011-03-01 07:25:09Z monkey $

	$xml = Xml::encode(range('a', 'z'), false, 'game', 'some');
	Xml::decode($xml);
*/
class LmXml {

	private $_parser;
	private $_document;
	private $_stack;
	private $_data;
	private $_last_opened_tag;
	private $_isnormal;
	private $_attrs  = [];
	private $_failed = FALSE;

	public function __construct($isnormal) {
		$this->_isnormal = $isnormal;
		$this->_parser   = xml_parser_create('Utf-8');
		xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->_parser, $this);
		xml_set_element_handler($this->_parser, 'open', 'close');
		xml_set_character_data_handler($this->_parser, 'data');
	}

	public function destruct() {
		xml_parser_free($this->_parser);
	}

	public function parse(&$data) {
		$this->_document = [];
		$this->_stack    = [];
		return xml_parse($this->_parser, $data, true) && !$this->_failed ? $this->_document : '';
	}

	public function open(&$parser, $tag, $attributes) {
		$this->_data   = '';
		$this->_failed = FALSE;
		if (!$this->_isnormal) {
			if (isset($attributes['id']) && !is_string($this->_document[$attributes['id']])) {
				$this->_document = &$this->_document[$attributes['id']];
			} else {
				$this->_failed = TRUE;
			}
		} else {
			if (!isset($this->_document[$tag]) || !is_string($this->_document[$tag])) {
				$this->_document = &$this->_document[$tag];
			} else {
				$this->_failed = TRUE;
			}
		}
		$this->_stack[]         = &$this->_document;
		$this->_last_opened_tag = $tag;
		$this->_attrs           = $attributes;
	}

	public function data(&$parser, $data) {
		if ($this->_last_opened_tag != NULL) {
			$this->_data .= $data;
		}
	}

	public function close(&$parser, $tag) {
		if ($this->_last_opened_tag == $tag) {
			$this->_document        = $this->_data;
			$this->_last_opened_tag = NULL;
		}
		array_pop($this->_stack);
		if ($this->_stack) {
			$this->_document = &$this->_stack[count($this->_stack) - 1];
		}
	}


	public static function decode(&$xml, $isnormal = false) {
		$xml_parser = new Xml($isnormal);
		$data       = $xml_parser->parse($xml);
		$xml_parser->destruct();
		return $data;
	}

	public static function encode($arr, $htmlon = false, $root = 'root', $item = 'item', $level = 1) {
		$s     = $level == 1 ? "<?xml version=\"1.0\" encoding=\"Utf-8\"?>\r\n<{$root}>\r\n" : '';
		$space = str_repeat("\t", $level);
		foreach ($arr as $k => $v) {
			if (!is_array($v)) {
				$s .= $space . "<{$item} id=\"$k\">" . ($htmlon ? '<![CDATA[' : '') . $v . ($htmlon ? ']]>' : '') . "</{$item}>\r\n";
			} else {
				$s .= $space . "<{$item} id=\"$k\">\r\n" . self::encode($v, $htmlon, $root, $item, $level + 1) . $space . "</{$item}>\r\n";
			}
		}
		$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
		return $level == 1 ? $s . "</{$root}>" : $s;
	}

}