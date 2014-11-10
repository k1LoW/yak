<?php

/**
 * Convert emojis for use with au mobile phone.
 *
 * PHP versions 4 and 5
 *
 * Copyright (c) 2009-2011 revulo
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */

/**
 * HTML_Emoji_Au
 *
 * Extended HTML_Emoji class for au.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Au extends HTML_Emoji
{
    /**
     * Whether the user agent is neither smartphone nor PC
     * @var    boolean
     */
    var $mobile = true;

    /**
     * Whether UTF-8 is a recommended encoding or not
     * @var    boolean
     */
    var $utf8 = false;

    /**
     * UTF-8 code area of emojis of other carriers
     * @var    string
     */
    var $_regexOthers = '/\xEE[\x80-\x9D][\x80-\xBF]/';

    /**
     * Unofficial Unicode code area of au emoji (with offsets for conversion)
     * @var    array
     */
    var $_sjismap = array(
        0xE234, 0xE272, 0x110C, 0xFFFF,
        0xE273, 0xE2EF, 0x110D, 0xFFFF,
        0xE2F0, 0xE32E, 0x1150, 0xFFFF,
        0xE32F, 0xE342, 0x1151, 0xFFFF,
        0xE468, 0xE4A6, 0x11D8, 0xFFFF,
        0xE4A7, 0xE523, 0x11D9, 0xFFFF,
        0xE524, 0xE562, 0x121C, 0xFFFF,
        0xE563, 0xE5DF, 0x121D, 0xFFFF,
    );

    /**
     * Unofficial Unicode code area of au emoji
     * @var    array
     */
    var $_utf8map = array(
        0xEC40, 0xECFC, 0x0700, 0xFFFF,
        0xED40, 0xED93, 0x0700, 0xFFFF,
        0xEF40, 0xEFFC, 0x0700, 0xFFFF,
        0xF040, 0xF0FC, 0x0700, 0xFFFF,
    );

    /**
     * Wrapper function for mb_convert_encoding().
     *
     * @param  string  $text
     * @param  string  $to
     * @param  string  $from
     * @return string
     */
    function convertEncoding($text, $to, $from)
    {
        $to   = $this->_normalizeEncoding($to);
        $from = $this->_normalizeEncoding($from);

        if ($from == 'SJIS-WIN' && $to == 'UTF-8') {
            return $this->_convertSjisToUtf8($text);
        }
        if ($from == 'UTF-8' && $to == 'SJIS-WIN') {
            return $this->_convertUtf8ToSjis($text);
        }
        if (($from == 'JIS' || $from == 'ISO-2022-JP-MS') && $to == 'UTF-8') {
            return $this->_convertJisToUtf8($text);
        }

        return mb_convert_encoding($text, $to, $from);
    }

    /**
     * Convert character encoding from Shift_JIS to UTF-8.
     *
     * @param  string  $text
     * @return string
     */
    function _convertSjisToUtf8($text)
    {
        $text = mb_encode_numericentity($text, $this->_sjismap, 'SJIS-win');
        $text = mb_convert_encoding($text, 'UTF-8', 'SJIS-win');
        return $this->decodeNumericentity($text, $this->_utf8map, 'UTF-8');
    }

    /**
     * Convert character encoding from UTF-8 to Shift_JIS.
     *
     * @param  string  $text
     * @return string
     */
    function _convertUtf8ToSjis($text)
    {
        $text = mb_encode_numericentity($text, $this->_utf8map, 'UTF-8');
        $text = mb_convert_encoding($text, 'SJIS-win', 'UTF-8');
        return $this->decodeNumericentity($text, $this->_sjismap, 'SJIS-win');
    }

    /**
     * Convert character encoding from ISO-2022-JP to UTF-8.
     *
     * @param  string  $text
     * @return string
     */
    function _convertJisToUtf8($text)
    {
        $backup = mb_substitute_character();
        mb_substitute_character('long');
        $text = mb_convert_encoding($text, 'UTF-8', 'JIS');
        mb_substitute_character($backup);

        $pattern  = '/JIS\+([0-9A-F]{4})/';
        $callback = array($this, '_fallbackJisToUtf8');
        $text     = preg_replace_callback($pattern, $callback, $text);

        return $text;
    }

    /**
     * Callback function called by _convertJisToUtf8() method.
     *
     * This function converts hexadecimal JIS code to UTF-8 binary data.
     *
     * @param  array   $matches
     * @return string
     */
    function _fallbackJisToUtf8($matches)
    {
        $jis  = "\x1B\x24\x42" . pack('H*', $matches[1]);
        $sjis = mb_convert_encoding($jis, 'SJIS', 'JIS');
        $num  = hexdec(bin2hex($sjis));

        if ($num >= 0xEB40) {
            if ($num <= 0xECFC) {
                $sjis = pack('n', $num + 0x0B00);
            } else if ($num <= 0xEE93) {
                $sjis = pack('n', $num + 0x0600);
            }
        }

        return $this->_convertSjisToUtf8($sjis);
    }
}
