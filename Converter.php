<?php

class Converter
{
    public static function convert($message)
    {
	$content = '';
	$terms = array();
        $big5ed = false;
        $colored = null;
	$last_class = null;

	for ($i = 0; $i < strlen($message); $i ++) {
	    if (ord($message[$i]) == 27) {
		$next = $message[++$i];
		if ($next == '[') {  // 控制碼
		    $code = '';
		    do {
			$code .= $message[++$i];
		    } while (!preg_match('#^[a-zA-Z]$#', $message[$i]));
		    switch ($code[strlen($code) - 1]) {
		    case 'm':
			$code = rtrim($code, 'm');
			$light = false;
			$foreground = $background = null;

			if (!is_null($colored)) {
			    list($light, $background, $foreground) = $colored;
			    $colored = null;
			    $content .= '</span>';
			}
			$codes = explode(';', $code);
			if (in_array('', $codes)) {
			    $foreground = $background = null;
			    $light = false;
			}
			foreach ($codes as $c) {
			    if ($c == 1) {
				$light = true;
			    } elseif ($c >= 30 and $c < 40) {
				$foreground = 'fg_' . ($c - 30);
			    } elseif ($c >= 40 and $c < 50) {
				$background = 'bg_' . ($c - 40);
			    }
			}

			$class = array();
			if ($light and $foreground) {
			    $class[] = 'light_' . $foreground;
			} elseif ($foreground) {
			    $class[] = $foreground;
			}

			if ($background) {
			    $class[] = $background;
			}
			$colored = array($light, $background, $foreground);
			$last_class = $class;
			if ($big5ed) {
			    $class[] = 'right_word';
			}
			$content .= "<span class=\"" . implode(' ', $class) . "\">";
			break;
		    default:
			die('parse failed');
		    }
		} else {
		    die('parse failed');
		}
	    } elseif (!$big5ed and ord($message[$i]) >= 0x81 and ord($message[$i]) <= 0xfe) { // Big5 前一個字
		// 先找到下一個字
		$j = $i + 1;
		$is_double = false;
		if (ord($message[$j]) == 27) {
		    $is_double = true;
		    if ('[' == $message[$j]) {
			$j ++;
		    }
		    while ('m' !== $message[$j]) {
			$j ++;
		    }
		    $j ++;
		}

		$content .= htmlspecialchars(self::big5ToUTF8($message[$i] . $message[$j]));
		$last_word = self::big5ToUTF8($message[$i] . $message[$j]);
		$big5ed = true;
	    } elseif ($big5ed and ((ord($message[$i]) >= 0x40 and ord($message[$i]) <= 0x7e) or (ord($message[$i]) >= 0xa1 and ord($message[$i]) <= 0xfe))) { // Big5 後一個字
		if ($is_double) {
		    $content .= $last_word . "</span>";
		    $content .= "<span class=\"" . implode(' ', $last_class) . "\">";
		}
		$big5ed = false;
	    } else {
                $content .= htmlspecialchars($message[$i]);
		$big5ed = false;
	    }
	}

	if ($colored) {
	    $content .= '</span>';
	}

        return $content;
    }

    static protected $_maps = null;

    static public function big5ToUTF8($word)
    {
	if (is_null(self::$_maps)) {
	    $fp = fopen(__DIR__ . '/big5uni.txt', 'r');
	    self::$_maps = array();
	    while (false !== ($line = fgets($fp))) {
		if (0 === strpos($line, '#')) {
		    continue;
		}

		list($big5, $utf8) = explode(' ', trim($line), 2);
		$utf8_word = html_entity_decode("&#" . hexdec($utf8) . ";");
		self::$_maps[hexdec($big5) / 256][hexdec($big5) % 256] = $utf8_word;
	    }
	    fclose($fp);
	}
	$chars = unpack('C2', $word);
	return self::$_maps[$chars[1]][$chars[2]];
    }
}
