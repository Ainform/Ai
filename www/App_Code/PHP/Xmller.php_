<?php

class Xmller{
	
	var $arrays = array();
	
	function __construct(){
		
	}

	function load($file){
		$parsed_xml =& new Xml($file);
		return $this->arrays = $this->reverse($parsed_xml);	
	}
	
	function reverse($object) {
		$out = array();
		if (is_a($object, 'XmlNode')) {
			$out = $object->toArray();
			return $out;
		} else if (is_object($object)) {
			$keys = get_object_vars($object);
			if (isset($keys['_name_'])) {
				$identity = $keys['_name_'];
				unset($keys['_name_']);
			}
			$new = array();
			foreach ($keys as $key => $value) {
				if (is_array($value)) {
					$new[$key] = (array)$this->reverse($value);
				} else {
					if (isset($value->_name_)) {
						$new = array_merge($new, $this->reverse($value));
					} else {
						$new[$key] = $this->reverse($value);
					}
				}
			}
			if (isset($identity)) {
				$out[$identity] = $new;
			} else {
				$out = $new;
			}
		} elseif (is_array($object)) {
			foreach ($object as $key => $value) {
				$out[$key] = $this->reverse($value);
			}
		} else {
			$out = $object;
		}
		return $out;
	}
}


class XmlNode  {

	var $name = null;
	var $namespace = null;
	var $namespaces = array();
	var $value;
	var $attributes = array();
	var $children = array();
	var $__parent = null;

	function __construct($name = null, $value = null, $namespace = null) {
		if (strpos($name, ':') !== false) {
			list($prefix, $name) = explode(':', $name);
			if (!$namespace) {
				$namespace = $prefix;
			}
		}
		$this->name = $name;
		if ($namespace) {
			$this->namespace = $namespace;
		}

		if (is_array($value) || is_object($value)) {
			$this->normalize($value);
		} elseif (!empty($value) || $value === 0 || $value === '0') {
			$this->createTextNode($value);
		}
	}

	function &createElement($name = null, $value = null, $attributes = array(), $namespace = false) {
		$element =& new XmlElement($name, $value, $attributes, $namespace);
		$element->setParent($this);
		return $element;
	}

	function setParent(&$parent) {
		if (strtolower(get_class($this)) == 'xml') {
			return;
		}
		if (isset($this->__parent) && is_object($this->__parent)) {
			if ($this->__parent->compare($parent)) {
				return;
			}
			foreach ($this->__parent->children as $i => $child) {
				if ($this->compare($child)) {
					array_splice($this->__parent->children, $i, 1);
					break;
				}
			}
		}
		if ($parent == null) {
			unset($this->__parent);
		} else {
			$parent->children[] =& $this;
			$this->__parent =& $parent;
		}
	}

	function cloneNode() {
		return clone($this);
	}

	function compare($node) {
		$keys = array(get_object_vars($this), get_object_vars($node));
		return ($keys[0] === $keys[1]);
	}

	function &append(&$child, $options = array()) {
		if (empty($child)) {
			$return = false;
			return $return;
		}

		if (is_object($child)) {
			if ($this->compare($child)) {
				trigger_error('Cannot append a node to itself.');
				$return = false;
				return $return;
			}
		} else if (is_array($child)) {
			$child = Set::map($child);
			if (is_array($child)) {
				if (!is_a(current($child), 'XmlNode')) {
					foreach ($child as $i => $childNode) {
						$child[$i] = $this->normalize($childNode, null, $options);
					}
				} else {
					foreach ($child as $childNode) {
						$this->append($childNode, $options);
					}
				}
				return $child;
			}
		} else {
			$attributes = array();
			if (func_num_args() >= 2) {
				$attributes = func_get_arg(1);
			}
			$child =& $this->createNode($child, null, $attributes);
		}

		$child = $this->normalize($child, null, $options);

		if (empty($child->namespace) && !empty($this->namespace)) {
			$child->namespace = $this->namespace;
		}

		if (is_a($child, 'XmlNode')) {
			$child->setParent($this);
		}

		return $child;
	}

	function &first() {
		if (isset($this->children[0])) {
			return $this->children[0];
		} else {
			$return = null;
			return $return;
		}
	}

	function &last() {
		if (count($this->children) > 0) {
			return $this->children[count($this->children) - 1];
		} else {
			$return = null;
			return $return;
		}
	}

	function &child($id) {
		$null = null;

		if (is_int($id)) {
			if (isset($this->children[$id])) {
				return $this->children[$id];
			} else {
				return null;
			}
		} elseif (is_string($id)) {
			for ($i = 0; $i < count($this->children); $i++) {
				if ($this->children[$i]->name == $id) {
					return $this->children[$i];
				}
			}
		}
		return $null;
	}

	function children($name) {
		$nodes = array();
		$count = count($this->children);
		for ($i = 0; $i < $count; $i++) {
			if ($this->children[$i]->name == $name) {
				$nodes[] =& $this->children[$i];
			}
		}
		return $nodes;
	}

	function &nextSibling() {
		$null = null;
		$count = count($this->__parent->children);
		for ($i = 0; $i < $count; $i++) {
			if ($this->__parent->children[$i] == $this) {
				if ($i >= $count - 1 || !isset($this->__parent->children[$i + 1])) {
					return $null;
				}
				return $this->__parent->children[$i + 1];
			}
		}
		return $null;
	}

	function &previousSibling() {
		$null = null;
		$count = count($this->__parent->children);
		for ($i = 0; $i < $count; $i++) {
			if ($this->__parent->children[$i] == $this) {
				if ($i == 0 || !isset($this->__parent->children[$i - 1])) {
					return $null;
				}
				return $this->__parent->children[$i - 1];
			}
		}
		return $null;
	}

	function &parent() {
		return $this->__parent;
	}

	function &document() {
		$document =& $this;
		while (true) {
			if (get_class($document) == 'Xml' || $document == null) {
				break;
			}
			$document =& $document->parent();
		}
		return $document;
	}

	function hasChildren() {
		if (is_array($this->children) && count($this->children) > 0) {
			return true;
		}
		return false;
	}

	function toString($options = array(), $depth = 0) {
		if (is_int($options)) {
			$depth = $options;
			$options = array();
		}
		$defaults = array('cdata' => true, 'whitespace' => false, 'convertEntities' => false, 'showEmpty' => true, 'leaveOpen' => false);
		$options = array_merge($defaults, Xml::options(), $options);
		$tag = !(strpos($this->name, '#') === 0);
		$d = '';

		if ($tag) {
			if ($options['whitespace']) {
				$d .= str_repeat("\t", $depth);
			}

			$d .= '<' . $this->name();
			if (count($this->namespaces) > 0) {
				foreach ($this->namespaces as $key => $val) {
					$val = str_replace('"', '\"', $val);
					$d .= ' xmlns:' . $key . '="' . $val . '"';
				}
			}

			$parent =& $this->parent();
			if ($parent->name === '#document' && count($parent->namespaces) > 0) {
				foreach ($parent->namespaces as $key => $val) {
					$val = str_replace('"', '\"', $val);
					$d .= ' xmlns:' . $key . '="' . $val . '"';
				}
			}

			if (is_array($this->attributes) && count($this->attributes) > 0) {
				foreach ($this->attributes as $key => $val) {
					$d .= ' ' . $key . '="' . htmlspecialchars($val, ENT_QUOTES, Configure::read('App.encoding')) . '"';
				}
			}
		}

		if (!$this->hasChildren() && empty($this->value) && $this->value !== 0 && $tag) {
			if (!$options['leaveOpen']) {
				$d .= ' />';
			}
			if ($options['whitespace']) {
				$d .= "\n";
			}
		} elseif ($tag || $this->hasChildren()) {
			if ($tag) {
				$d .= '>';
			}
			if ($this->hasChildren()) {
				if ($options['whitespace']) {
					$d .= "\n";
				}

				$count = count($this->children);
				$cDepth = $depth + 1;
				for ($i = 0; $i < $count; $i++) {
					$d .= $this->children[$i]->toString($options, $cDepth);
				}
				if ($tag) {
					if ($options['whitespace'] && $tag) {
						$d .= str_repeat("\t", $depth);
					}
					if (!$options['leaveOpen']) {
						$d .= '</' . $this->name() . '>';
					}
					if ($options['whitespace']) {
						$d .= "\n";
					}
				}
			}
		}
		return $d;
	}

	function camelize($lowerCaseAndUnderscoredWord) {
		return str_replace(" ", "", ucwords(str_replace("_", " ", $lowerCaseAndUnderscoredWord)));
	}

	function toArray($camelize = true) {
		$out = $this->attributes;
		$multi = null;

		foreach ($this->children as $child) {
			$key = $camelize ? $this->camelize($child->name) : $child->name;

			if (is_a($child, 'XmlTextNode')) {
				$out['value'] = $child->value;
				continue;
			} elseif (isset($child->children[0]) && is_a($child->children[0], 'XmlTextNode')) {
				$value = $child->children[0]->value;

				if ($child->attributes) {
					$value = array_merge(array('value' => $value), $child->attributes);
				}

				if (isset($out[$child->name]) || isset($multi[$key])) {
					if (!isset($multi[$key])) {
						$multi[$key] = array($out[$child->name]);
						unset($out[$child->name]);
					}
					$multi[$key][] = $value;
				} else {
					$out[$child->name] = $value;
				}
				continue;
			} else {
				$value = $child->toArray($camelize);
			}

			if (!isset($out[$key])) {
				$out[$key] = $value;
			} else {
				if (!is_array($out[$key]) || !isset($out[$key][0])) {
					$out[$key] = array($out[$key]);
				}
				$out[$key][] = $value;
			}
		}

		if (isset($multi)) {
			$out = array_merge($out, $multi);
		}
		return $out;
	}

	function __toString() {
		return $this->toString();
	}

	function __killParent($recursive = true) {
		unset($this->__parent, $this->_log);
		if ($recursive && $this->hasChildren()) {
			for ($i = 0; $i < count($this->children); $i++) {
				$this->children[$i]->__killParent(true);
			}
		}
	}
}


class Xml extends XmlNode {

	var $__parser;

	var $__file;

	var $__header = null;

	var $__tags = array();

	var $version = '1.0';

	var $encoding = 'UTF-8';

	function __construct($input = null, $options = array()) {
		$defaults = array(
			'root' => '#document', 'tags' => array(), 'namespaces' => array(),
			'version' => '1.0', 'encoding' => 'UTF-8', 'format' => 'attributes'
		);
		$options = array_merge($defaults, Xml::options(), $options);

		foreach (array('version', 'encoding', 'namespaces') as $key) {
			$this->{$key} = $options[$key];
		}
		$this->__tags = $options['tags'];
		parent::__construct($options['root']);

		if (!empty($input)) {
			if (is_string($input)) {
				$this->load($input);
			} elseif (is_array($input) || is_object($input)) {
				$this->append($input, $options);
			}
		}

	}

	function load($input) {
		if (!is_string($input)) {
			return false;
		}
		$this->__rawData = null;
		$this->__header = null;

		if (strstr($input, "<")) {
			$this->__rawData = $input;
		} elseif (strpos($input, 'http://') === 0 || strpos($input, 'https://') === 0) {
			App::import('Core', 'HttpSocket');
			$socket = new HttpSocket();
			$this->__rawData = $socket->get($input);
		} elseif (file_exists($input)) {
			$this->__rawData = file_get_contents($input);
		} else {
			trigger_error('XML cannot be read');
			return false;
		}
		return $this->parse();
	}

	function parse() {
		$this->__initParser();
		$this->__header = trim(str_replace(array('<' . '?', '?' . '>'), array('', ''), substr(trim($this->__rawData), 0, strpos($this->__rawData, "\n"))));

		xml_parse_into_struct($this->__parser, $this->__rawData, $vals);
		$xml =& $this;
		$count = count($vals);

		for ($i = 0; $i < $count; $i++) {
			$data = $vals[$i];
			$data = array_merge(array('tag' => null, 'value' => null, 'attributes' => array()), $data);
			switch ($data['type']) {
				case "open" :
					$xml =& $xml->createElement($data['tag'], $data['value'], $data['attributes']);
				break;
				case "close" :
					$xml =& $xml->parent();
				break;
				case "complete" :
					$xml->createElement($data['tag'], $data['value'], $data['attributes']);
				break;
				case 'cdata':
					$xml->createTextNode($data['value']);
				break;
			}
		}
		return true;
	}

	function __initParser() {
		if (empty($this->__parser)) {
			$this->__parser = xml_parser_create();
			xml_set_object($this->__parser, $this);
			xml_parser_set_option($this->__parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($this->__parser, XML_OPTION_SKIP_WHITE, 1);
		}
	}

	function compose($options = array()) {
		return $this->toString($options);
	}

	function error($msg, $code = 0, $line = 0) {
		if (Configure::read('debug')) {
			echo $msg . " " . $code . " " . $line;
		}
	}

	function getError($code) {
		$r = @xml_error_string($code);
		return $r;
	}

	
	function &next() {
		$return = null;
		return $return;
	}

	function &previous() {
		$return = null;
		return $return;
	}

	function &parent() {
		$return = null;
		return $return;
	}

	function addNamespace($prefix, $url) {
		if ($count = count($this->children)) {
			for ($i = 0; $i < $count; $i++) {
				$this->children[$i]->addNamespace($prefix, $url);
			}
			return true;
		}
		return parent::addNamespace($prefix, $url);
	}

	function removeNamespace($prefix) {
		if ($count = count($this->children)) {
			for ($i = 0; $i < $count; $i++) {
				$this->children[$i]->removeNamespace($prefix);
			}
			return true;
		}
		return parent::removeNamespace($prefix);
	}

	function toString($options = array()) {
		if (is_bool($options)) {
			$options = array('header' => $options);
		}

		$defaults = array('header' => false, 'encoding' => $this->encoding);
		$options = array_merge($defaults, Xml::options(), $options);
		$data = parent::toString($options, 0);

		if ($options['header']) {
			if (!empty($this->__header)) {
				return $this->header($this->__header)  . "\n" . $data;
			}
			return $this->header()  . "\n" . $data;
		}

		return $data;
	}

	function header($attrib = array()) {
		$header = 'xml';
		if (is_string($attrib)) {
			$header = $attrib;
		} else {

			$attrib = array_merge(array('version' => $this->version, 'encoding' => $this->encoding), $attrib);
			foreach ($attrib as $key=>$val) {
				$header .= ' ' . $key . '="' . $val . '"';
			}
		}
		return '<' . '?' . $header . ' ?' . '>';
	}

	function __destruct() {
		if (is_resource($this->__parser)) {
			xml_parser_free($this->__parser);
		}
	}

	function addGlobalNs($name, $url = null) {
		$_this =& XmlManager::getInstance();
		if ($ns = Xml::resolveNamespace($name, $url)) {
			$_this->namespaces = array_merge($_this->namespaces, $ns);
			return $ns;
		}
		return false;
	}

	function resolveNamespace($name, $url) {
		$_this =& XmlManager::getInstance();
		if ($url == null && isset($_this->defaultNamespaceMap[$name])) {
			$url = $_this->defaultNamespaceMap[$name];
		} elseif ($url == null) {
			return false;
		}

		if (!strpos($url, '://') && isset($_this->defaultNamespaceMap[$name])) {
			$_url = $_this->defaultNamespaceMap[$name];
			$name = $url;
			$url = $_url;
		}
		return array($name => $url);
	}

	function addGlobalNamespace($name, $url = null) {
		return Xml::addGlobalNs($name, $url);
	}

	function removeGlobalNs($name) {
		$_this =& XmlManager::getInstance();
		if (isset($_this->namespaces[$name])) {
			unset($_this->namespaces[$name]);
			unset($this->namespaces[$name]);
			return true;
		} elseif (in_array($name, $_this->namespaces)) {
			$keys = array_keys($_this->namespaces);
			$count = count($keys);
			for ($i = 0; $i < $count; $i++) {
				if ($_this->namespaces[$keys[$i]] == $name) {
					unset($_this->namespaces[$keys[$i]]);
					unset($this->namespaces[$keys[$i]]);
					return true;
				}
			}
		}
		return false;
	}

	function removeGlobalNamespace($name) {
		return Xml::removeGlobalNs($name);
	}

	function options($options = array()) {
		$_this =& XmlManager::getInstance();
		$_this->options = array_merge($_this->options, $options);
		return $_this->options;
	}
}

class XmlElement extends XmlNode {

	function __construct($name = null, $value = null, $attributes = array(), $namespace = false) {
		parent::__construct($name, $value, $namespace);
		$this->addAttribute($attributes);
	}

	function attributes() {
		return $this->attributes;
	}

	function addAttribute($name, $val = null) {
		if (is_object($name)) {
			$name = get_object_vars($name);
		}
		if (is_array($name)) {
			foreach ($name as $key => $val) {
				$this->addAttribute($key, $val);
			}
			return true;
		}
		if (is_numeric($name)) {
			$name = $val;
			$val = null;
		}
		if (!empty($name)) {
			if (strpos($name, 'xmlns') === 0) {
				if ($name == 'xmlns') {
					$this->namespace = $val;
				} else {
					list($pre, $prefix) = explode(':', $name);
					$this->addNamespace($prefix, $val);
					return true;
				}
			}
			$this->attributes[$name] = $val;
			return true;
		}
		return false;
	}

	function removeAttribute($attr) {
		if (array_key_exists($attr, $this->attributes)) {
			unset($this->attributes[$attr]);
			return true;
		}
		return false;
	}
}

class XmlManager {

/**
 * Global XML namespaces.  Used in all XML documents processed by this application
 *
 * @var array
 * @access public
 */
	var $namespaces = array();
/**
 * Global XML document parsing/generation settings.
 *
 * @var array
 * @access public
 */
	var $options = array();
/**
 * Map of common namespace URIs
 *
 * @access private
 * @var array
 */
	var $defaultNamespaceMap = array(
		'dc'     => 'http://purl.org/dc/elements/1.1/',					// Dublin Core
		'dct'    => 'http://purl.org/dc/terms/',						// Dublin Core Terms
		'g'			=> 'http://base.google.com/ns/1.0',					// Google Base
		'rc'		=> 'http://purl.org/rss/1.0/modules/content/',		// RSS 1.0 Content Module
		'wf'		=> 'http://wellformedweb.org/CommentAPI/',			// Well-Formed Web Comment API
		'fb'		=> 'http://rssnamespace.org/feedburner/ext/1.0',	// FeedBurner extensions
		'lj'		=> 'http://www.livejournal.org/rss/lj/1.0/',		// Live Journal
		'itunes'	=> 'http://www.itunes.com/dtds/podcast-1.0.dtd',	// iTunes
		'xhtml'		=> 'http://www.w3.org/1999/xhtml',					// XHTML,
		'atom'	 	=> 'http://www.w3.org/2005/Atom'					// Atom
	);
/**
 * Returns a reference to the global XML object that manages app-wide XML settings
 *
 * @return object
 * @access public
 */
	function &getInstance() {
		static $instance = array();

		if (!$instance) {
			$instance[0] =& new XmlManager();
		}
		return $instance[0];
	}
}
?>