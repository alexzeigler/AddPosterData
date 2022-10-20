<?php
	/**
	 * This file contains code I did not write. All credit for this code goes to nachazo on Github: https://github.com/nachazo/scryfalldler
	 *
	 * The license for this code can be found here: https://github.com/nachazo/scryfalldler/blob/master/LICENSE
	 *
	 * This project is intended as a personal project and is not intended as a commercial product.
	 *
	 */
	
	
	function line(string $text, $colorText = null, $colorBackground = null, bool $addDate = false, bool $breakLine = true, bool $eraseLine = false): ?string {
		$res = null;
		if ($eraseLine) {
			echo "\r\e[K";
		}
		if ($addDate == true) {
			$res .= date("Y-m-d H:i:s") . ":";
		}
		$spaces = null;
		if ($breakLine == true)
			$spaces = " ";
		$res .= (new Colors())->getColoredString($spaces . $text . $spaces, $colorText, $colorBackground);
		if ($breakLine == true)
			$res .= "\n";
		if ($eraseLine) {
			$res .= "\r";
		}
		return $res;
	}
	
	class Colors {
		private static array $foreground_colors = array(
			'black'        => '0;30',
			'gray'         => '0;30',
			'dark_gray'    => '1;30',
			'blue'         => '0;34',
			'light_blue'   => '1;34',
			'green'        => '0;32',
			'light_green'  => '1;32',
			'cyan'         => '0;36',
			'light_cyan'   => '1;36',
			'red'          => '0;31',
			'light_red'    => '1;31',
			'purple'       => '0;35',
			'light_purple' => '1;35',
			'brown'        => '0;33',
			'yellow'       => '0;33',
			'light_yellow' => '1;33',
			'light_gray'   => '0;37',
			'white'        => '1;37',
			'b'            => '1'
		);
		private static array $background_colors = array(
			'black'      => '40',
			'red'        => '41',
			'green'      => '42',
			'yellow'     => '43',
			'blue'       => '44',
			'magenta'    => '45',
			'cyan'       => '46',
			'light_gray' => '47'
		);
		
		public function getColoredString($string, $foreground_color = null, $background_color = null): array|string {
			$colored_string = "";
			
			if (isset(self::$foreground_colors[$foreground_color])) {
				$colored_string .= "\033[" . self::$foreground_colors[$foreground_color] . "m";
			}
			if (isset(self::$background_colors[$background_color])) {
				$colored_string .= "\033[" . self::$background_colors[$background_color] . "m";
			}
			
			$colored_string .= $string . "\033[0m";
			
			foreach (self::$foreground_colors as $color => $value) {
				if (str_contains($colored_string, "<" . $color . ">")) {
					$colored_string = str_replace("<" . $color . ">", "\033[" . $value . "m", $colored_string);
					$colored_string = str_replace("</" . $color . ">", "\033[0m", $colored_string);
				}
			}
			
			return $colored_string;
		}
		
	}