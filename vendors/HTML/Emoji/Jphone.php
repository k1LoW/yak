<?php

/**
 * Convert emojis for use with SoftBank 2G mobile phone.
 *
 * PHP versions 4 and 5
 *
 * Copyright (c) 2009-2010 revulo
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
 * @copyright  2009-2010 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */

require_once dirname(__FILE__) . '/Softbank.php';

/**
 * HTML_Emoji_Jphone
 *
 * Extended HTML_Emoji class for SoftBank 2G mobile phone.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2010 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Jphone extends HTML_Emoji_Softbank
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
     * Convert character encoding from Shift_JIS to UTF-8.
     *
     * @param  string  $text
     * @return string
     */
    function _convertSjisToUtf8($text)
    {
        $pattern  = '/\x1B\x24([\x45-\x47\x4F-\x51][\x21-\x7A]+)\x0F?/';
        $callback = array($this, '_convertWebcodeToEntity');
        $text = preg_replace_callback($pattern, $callback, $text);

        $text = mb_convert_encoding($text, 'UTF-8', 'SJIS-win');
        $text = mb_decode_numericentity($text, $this->_utf8map, 'UTF-8');

        return $text;
    }

    /**
     * Callback function called by _convertSjisToUtf8() method.
     *
     * This function converts webcode to numeric character reference.
     *
     * @param  array   $matches
     * @return string
     */
    function _convertWebcodeToEntity($matches)
    {
        $webcode = $matches[1];
        $high = ord($webcode[0]);

        if ($high === 0x45 || $high === 0x46) {
            $offset = 0x9BE0;
        } else if ($high === 0x47) {
            $offset = 0x98E0;
        } else {
            $offset = 0x93E0;
        }

        $str = '';
        $len = mb_strlen($webcode, 'ASCII');
        for ($i = 1; $i < $len; ++$i) {
            $low = ord($webcode[$i]);
            $unicode = ($high << 8) + $low + $offset;
            $str .= '&#' . $unicode . ';';
        }

        return $str;
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

        $pattern  = '/&#(5\d{4});/';
        $callback = array($this, '_convertUnicodeToWebcode');
        $text = preg_replace_callback($pattern, $callback, $text);

        return $text;
    }

    /**
     * Callback function called by _convertUtf8ToSjis() method.
     *
     * This function converts Unicode decimal number to webcode binary data.
     *
     * @param  array   $matches
     * @return string
     */
    function _convertUnicodeToWebcode($matches)
    {
        $unicode = intval($matches[1]);

        if ($unicode < 0xE100) {
            $offset = 0x98E0;
        } else if ($unicode < 0xE300) {
            $offset = 0x9BE0;
        } else {
            $offset = 0x93E0;
        }

        $webcode = $unicode - $offset;
        return "\x1B\x24" . pack('n', $webcode) . "\x0F";
    }
}
