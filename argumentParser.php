<?php
	/**
	 * This file contains code I did not write. All credit for this code goes to DenesKellner on Github: https://gist.github.com/DenesKellner/29a696e4420a4f3e162a935e5cb9ab4f
	 *
	 * This project is intended as a personal project and is not intended as a commercial product.
	 *
	 */
	
	function arg($x = "", $default = null) {
		
		static $arginfo = [];
		
		/* helper */
		$contains = function($h, $n) {
			return (str_contains($h, $n));
		};
		/* helper */
		$valuesOf = function($s) {
			return explode(",", $s);
		};
		
		//  called with a multiline string --> parse arguments
		if ($contains($x, "\n")) {
			
			//  parse multiline text input
			$args = $GLOBALS["argv"] ? : [];
			$rows = preg_split('/\s*\n\s*/', trim($x));
			$data = $valuesOf("char,word,type,help");
			foreach ($rows as $row) {
				list($char, $word, $type, $help) = preg_split('/\s\s+/', $row);
				$char = trim($char, "-");
				$word = trim($word, "-");
				$key = $word ? : $char ? : "";
				if ($key === "")
					continue;
				$arginfo[$key] = compact($data);
				$arginfo[$key]["value"] = null;
			}
			
			$nr = 0;
			while ($args) {
				
				$x = array_shift($args);
				if ($x[0] <> "-") {
					$arginfo[$nr++]["value"] = $x;
					continue;
				}
				$x = ltrim($x, "-");
				$v = null;
				if ($contains($x, "="))
					list($x, $v) = explode("=", $x, 2);
				$k = "";
				foreach ($arginfo as $k => $arg)
					if (($arg["char"] == $x) || ($arg["word"] == $x))
						break;
				$t = $arginfo[$k]["type"];
				switch ($t) {
					case "bool" :
						$v = true;
						break;
					case "str"  :
						if (is_null($v))
							$v = array_shift($args);
						break;
					case "int"  :
						if (is_null($v))
							$v = array_shift($args);
						$v = intval($v);
						break;
				}
				$arginfo[$k]["value"] = $v;
				
			}
			
			return $arginfo;
			
		}
		
		//  called with a question --> read argument value
		if ($x === "")
			return $arginfo;
		if (isset($arginfo[$x]["value"]))
			return $arginfo[$x]["value"];
		return $default;
		
	}