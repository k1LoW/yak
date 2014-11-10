<?php

/**
 * Convert emojis for use with PC.
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
 * HTML_Emoji_Pc
 *
 * Extended HTML_Emoji class for PC.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Pc extends HTML_Emoji
{
    /**
     * Whether the user agent is neither smartphone nor PC
     * @var    boolean
     */
    var $mobile = false;

    /**
     * Whether UTF-8 is a recommended encoding or not
     * @var    boolean
     */
    var $utf8 = true;

    /**
     * Base URL to image files
     * @var    string
     */
    var $_imageUrl = '';

    /**
     * Set the base URL to image files.
     *
     * @param  string  $url
     */
    function setImageUrl($url)
    {
        if ($url !== '' && substr($url, -1) !== '/') {
            $url .= '/';
        }
        $this->_imageUrl = $url;
    }

    /**
     * Return width and height of the image.
     *
     * @param  string  $sjis
     * @return array
     */
    function _getImageSize($sjis)
    {
        $high = ord($sjis[0]);

        if ($high < 0xF0) {
            // SoftBank
            $width  = 15;
            $height = 15;
        } else if ($high === 0xF8 || $high === 0xF9) {
            // NTT docomo
            $width  = 12;
            $height = 12;
        } else {
            // au
            if ($sjis === "\xF7\xAB") {
                // blankquarter
                $width = 4;
            } else if ($sjis === "\xF7\xAA") {
                // blankhalf
                $width = 7;
            } else {
                $width = 14;
            }
            $height = 15;
        }

        return array($width, $height);
    }

    /**
     * Callback function called by convertCarrier() method.
     *
     * This function converts an emoji to <img> tag.
     *
     * @param  array   $matches
     * @return string
     */
    function _convertCharacter($matches)
    {
        if (isset($this->_translationTable) === false) {
            $this->_initTranslationTable();
        }

        $utf8 = $matches[0];
        if (isset($this->_translationTable[$utf8]) === true) {
            $sjis = $this->_translationTable[$utf8];
            list($width, $height) = $this->_getImageSize($sjis);

            return '<img class="emoji"'
                 . ' src="' . $this->_imageUrl . bin2hex($sjis) . '.gif"'
                 . ' alt=""'
                 . ' width="'  . $width  . '"'
                 . ' height="' . $height . '" />';
        } else {
            return $utf8;
        }
    }
}
