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

require_once dirname(__FILE__) . '/Au.php';

/**
 * HTML_Emoji_Aumail
 *
 * Extended HTML_Emoji class for au e-mail.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Aumail extends HTML_Emoji_Au
{
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

        $pattern  = '/&#(6\d{4});/';
        $callback = array($this, '_convertDecimalToSjis');
        $text = preg_replace_callback($pattern, $callback, $text);

        return $text;
    }

    /**
     * Callback function called by _convertUtf8ToSjis() method.
     *
     * This function converts Shift_JIS decimal code to Shift_JIS binary data.
     *
     * @param  array   $matches
     * @return string
     */
    function _convertDecimalToSjis($matches)
    {
        $num = intval($matches[1]);
        if ($num >= 0xF340) {
            if ($num <= 0xF493) {
                return pack('n', $num - 0x0600);
            } else if ($num <= 0xF7FC) {
                return pack('n', $num - 0x0B00);
            }
        }
        return $matches[0];
    }
}
