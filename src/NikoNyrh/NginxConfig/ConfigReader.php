<?php
namespace NikoNyrh\NginxConfig;

class ConfigReader {
	protected $conf;
	
	public function __construct($file) {
		$conf = array();
		$path = array();
		$node = &$conf;
		
		if (!is_array($file)) {
			$file = file($file);
		}
		
		foreach ($file as $line) {
			// Remove comments and whitespace
			$line = preg_replace('/#.+/', '', $line);
			$line = rtrim(trim($line), ';');
			
			if (empty($line)) {
				continue;
			}
			
			if (preg_match('/^(.*?)[ \t]*{$/', $line, $match)) {
				// A new block started, nest deeper
				$p = $match[1];
				
				if (!isset($node[$p])) {
					$node[$p] = array();
				}
				
				$path[] = &$node[$p];
				$node = &$node[$p];
			}
			elseif ($line == '}') {
				// A block ended, return back to the previous level
				array_pop($path);
				
				if (!empty($path)) {
					$node = &$path[sizeof($path)-1];
				}
			}
			else {
				$line = preg_split('/[ \t]+/', $line);
				$p = $line[0];
				unset($line[0]);
				
				$value = empty($line) ? null : (
					sizeof($line) > 1 ? array_values($line) : $line[1]
				);
				
				//TODO: Have a nicer treatment for possibly duplicate keys
				if (!isset($node[$p])) {
					$node[$p] = array($value);
				}
				else {
					$node[$p][] = $value;
				}
			}
		}
		
		$this->conf = $conf;
	}
	
	public function getConfig() {
		return $this->conf;
	}
}

