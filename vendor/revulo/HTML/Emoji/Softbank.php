<?php

/**
 * Convert emojis for use with SoftBank mobile phone.
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
 * HTML_Emoji_Softbank
 *
 * Extended HTML_Emoji class for SoftBank.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Softbank extends HTML_Emoji
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
    var $utf8 = true;

    /**
     * UTF-8 code area of emojis of other carriers
     * @var    string
     */
    var $_regexOthers = '/\xEE[\x98-\x9D\xB1-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/';

    /**
     * Unicode code area of SoftBank emoji
     * @var    array
     */
    var $_utf8map = array(
        0xE001, 0xE05A, 0x0000, 0xFFFF,
        0xE101, 0xE15A, 0x0000, 0xFFFF,
        0xE201, 0xE25A, 0x0000, 0xFFFF,
        0xE301, 0xE34D, 0x0000, 0xFFFF,
        0xE401, 0xE44C, 0x0000, 0xFFFF,
        0xE501, 0xE53E, 0x0000, 0xFFFF,
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
        $backup = mb_substitute_character();
        mb_substitute_character('long');
        $text = mb_convert_encoding($text, 'UTF-8', 'SJIS');
        mb_substitute_character($backup);

        $pattern  = '/(BAD|JIS)\+([0-9A-F]{4})/';
        $callback = array($this, '_fallbackSjisToUtf8');
        $text     = preg_replace_callback($pattern, $callback, $text);

        return $text;
    }

    /**
     * Callback function called by _convertSjisToUtf8() method.
     *
     * This function converts Shift_JIS hexadecimal code to UTF-8 binary data.
     *
     * @param  array   $matches
     * @return string
     */
    function _fallbackSjisToUtf8($matches)
    {
        if ($matches[1] === 'JIS') {
            $jis  = "\x1B\x24\x42" . pack('H*', $matches[2]);
            $sjis = mb_convert_encoding($jis, 'SJIS-win', 'JIS');
            return mb_convert_encoding($sjis, 'UTF-8', 'SJIS-win');
        } else {
            $sjis = hexdec($matches[2]);
            $high = $sjis >> 8;
            $low  = $sjis & 0xFF;

            if ($low > 0xA0) {
                if ($high === 0xF7 && $low <= 0xFA) {
                    $unicode = "\xE2" . chr($low - 0xA0);
                } else if ($high === 0xF9 && $low <= 0xED) {
                    $unicode = "\xE3" . chr($low - 0xA0);
                } else if ($high === 0xFB && $low <= 0xDE) {
                    $unicode = "\xE5" . chr($low - 0xA0);
                }
            } else if ($low > 0x40) {
                if ($low >= 0x80) {
                    --$low;
                }
                if ($high === 0xF7 && $low < 0x9B) {
                    $unicode = "\xE1" . chr($low - 0x40);
                } else if ($high === 0xF9 && $low < 0x9B) {
                    $unicode = "\xE0" . chr($low - 0x40);
                } else if ($high === 0xFB && $low < 0x8D) {
                    $unicode = "\xE4" . chr($low - 0x40);
                }
            }

            return mb_convert_encoding($unicode, 'UTF-8', 'UCS-2');
        }
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
        $callback = array($this, '_convertUnicodeToSjis');
        $text = preg_replace_callback($pattern, $callback, $text);

        return $text;
    }

    /**
     * Callback function called by _convertUtf8ToSjis() method.
     *
     * This function converts Unicode decimal number to Shift_JIS binary data.
     *
     * @param  array   $matches
     * @return string
     */
    function _convertUnicodeToSjis($matches)
    {
        $unicode = intval($matches[1]);
        $high = $unicode >> 8;
        $low  = $unicode & 0xFF;

        if ($high === 0xE0 || $high === 0xE1 || $high === 0xE4) {
            $low += 0x40;
            if ($low >= 0x7F) {
                ++$low;
            }
        } else {
            $low += 0xA0;
        }

        if ($high === 0xE1 || $high === 0xE2) {
            return "\xF7" . chr($low);
        } else if ($high === 0xE0 || $high === 0xE3) {
            return "\xF9" . chr($low);
        } else {
            return "\xFB" . chr($low);
        }
    }
}
