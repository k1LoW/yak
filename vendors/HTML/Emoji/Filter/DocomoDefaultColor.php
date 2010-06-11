<?php

/**
 * Display UTF-8 emojis of NTT docomo in the default color.
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

/**
 * HTML_Emoji_Filter_DocomoDefaultColor
 *
 * In NTT docomo mobile phones, emoji is treated as a text character and
 * its color is influenced by style settings. This filter forces emojis
 * to be displayed in the default color.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2010 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Filter_DocomoDefaultColor
{
    /**
     * Emoji color table for NTT docomo
     * @var    array
     */
    var $_colorTable = array(
        "\xEE\x98\xBE" => 'red',
        "\xEE\x98\xBF" => 'blue',
        "\xEE\x99\x80" => 'blue',
        "\xEE\x99\x81" => 'blue',
        "\xEE\x99\x82" => '#FF8000',
        "\xEE\x99\x83" => 'red',
        "\xEE\x99\x84" => 'blue',
        "\xEE\x99\x85" => 'blue',
        "\xEE\x99\x86" => 'red',
        "\xEE\x99\x87" => '#FF8000',
        "\xEE\x99\x88" => 'lime',
        "\xEE\x99\x89" => 'blue',
        "\xEE\x99\x8A" => 'red',
        "\xEE\x99\x8B" => '#FF8000',
        "\xEE\x99\x8C" => 'lime',
        "\xEE\x99\x8D" => 'blue',
        "\xEE\x99\x8E" => 'red',
        "\xEE\x99\x8F" => '#FF8000',
        "\xEE\x99\x90" => 'lime',
        "\xEE\x99\x91" => 'blue',
        "\xEE\x99\x92" => 'fuchsia',
        "\xEE\x99\x93" => 'black',
        "\xEE\x99\x94" => 'blue',
        "\xEE\x99\x95" => 'lime',
        "\xEE\x99\x96" => 'black',
        "\xEE\x99\x97" => 'blue',
        "\xEE\x99\x98" => '#FF8000',
        "\xEE\x99\x99" => 'black',
        "\xEE\x99\x9A" => 'fuchsia',
        "\xEE\x99\x9B" => 'lime',
        "\xEE\x99\x9C" => '#FF8000',
        "\xEE\x99\x9D" => 'blue',
        "\xEE\x99\x9E" => 'black',
        "\xEE\x99\x9F" => 'lime',
        "\xEE\x99\xA0" => 'red',
        "\xEE\x99\xA1" => 'blue',
        "\xEE\x99\xA2" => 'blue',
        "\xEE\x99\xA3" => 'red',
        "\xEE\x99\xA4" => 'blue',
        "\xEE\x99\xA5" => 'red',
        "\xEE\x99\xA6" => 'red',
        "\xEE\x99\xA7" => 'fuchsia',
        "\xEE\x99\xA8" => 'red',
        "\xEE\x99\xA9" => 'lime',
        "\xEE\x99\xAA" => 'blue',
        "\xEE\x99\xAB" => 'fuchsia',
        "\xEE\x99\xAC" => 'blue',
        "\xEE\x99\xAD" => 'black',
        "\xEE\x99\xAE" => 'black',
        "\xEE\x99\xAF" => 'black',
        "\xEE\x99\xB0" => 'lime',
        "\xEE\x99\xB1" => 'fuchsia',
        "\xEE\x99\xB2" => '#FF8000',
        "\xEE\x99\xB3" => '#FF8000',
        "\xEE\x99\xB4" => 'red',
        "\xEE\x99\xB5" => 'blue',
        "\xEE\x99\xB6" => 'black',
        "\xEE\x99\xB7" => 'black',
        "\xEE\x99\xB8" => 'black',
        "\xEE\x99\xB9" => '#FF8000',
        "\xEE\x99\xBA" => 'blue',
        "\xEE\x99\xBB" => 'fuchsia',
        "\xEE\x99\xBC" => 'black',
        "\xEE\x99\xBD" => 'red',
        "\xEE\x99\xBE" => '#FF8000',
        "\xEE\x99\xBF" => 'black',
        "\xEE\x9A\x80" => 'red',
        "\xEE\x9A\x81" => 'black',
        "\xEE\x9A\x82" => 'red',
        "\xEE\x9A\x83" => '#FF8000',
        "\xEE\x9A\x84" => 'red',
        "\xEE\x9A\x85" => 'red',
        "\xEE\x9A\x86" => 'red',
        "\xEE\x9A\x87" => 'black',
        "\xEE\x9A\x88" => 'black',
        "\xEE\x9A\x89" => '#FF8000',
        "\xEE\x9A\x8A" => 'blue',
        "\xEE\x9A\x8B" => 'black',
        "\xEE\x9A\x8C" => 'blue',
        "\xEE\x9A\x8D" => 'red',
        "\xEE\x9A\x8E" => 'black',
        "\xEE\x9A\x8F" => 'red',
        "\xEE\x9A\x90" => 'black',
        "\xEE\x9A\x91" => 'black',
        "\xEE\x9A\x92" => '#FF8000',
        "\xEE\x9A\x93" => '#FF8000',
        "\xEE\x9A\x94" => '#FF8000',
        "\xEE\x9A\x95" => '#FF8000',
        "\xEE\x9A\x96" => 'black',
        "\xEE\x9A\x97" => 'black',
        "\xEE\x9A\x98" => '#FF8000',
        "\xEE\x9A\x99" => 'black',
        "\xEE\x9A\x9A" => 'black',
        "\xEE\x9A\x9B" => 'blue',
        "\xEE\x9A\x9C" => 'black',
        "\xEE\x9A\x9D" => 'black',
        "\xEE\x9A\x9E" => 'black',
        "\xEE\x9A\x9F" => 'black',
        "\xEE\x9A\xA0" => 'black',
        "\xEE\x9A\xA1" => '#FF8000',
        "\xEE\x9A\xA2" => '#FF8000',
        "\xEE\x9A\xA3" => 'blue',
        "\xEE\x9A\xA4" => 'lime',
        "\xEE\x9A\xA5" => 'black',
        "\xEE\x9A\xA6" => 'blue',
        "\xEE\x9A\xA7" => 'blue',
        "\xEE\x9A\xA8" => 'black',
        "\xEE\x9A\xA9" => 'black',
        "\xEE\x9A\xAA" => 'black',
        "\xEE\x9A\xAB" => 'black',
        "\xEE\x9A\xAC" => 'black',
        "\xEE\x9A\xAD" => 'black',
        "\xEE\x9A\xAE" => 'black',
        "\xEE\x9A\xAF" => 'black',
        "\xEE\x9A\xB0" => 'black',
        "\xEE\x9A\xB1" => 'black',
        "\xEE\x9A\xB2" => 'black',
        "\xEE\x9A\xB3" => 'black',
        "\xEE\x9A\xB4" => 'black',
        "\xEE\x9A\xB5" => 'black',
        "\xEE\x9A\xB6" => 'black',
        "\xEE\x9A\xB7" => 'black',
        "\xEE\x9A\xB8" => 'black',
        "\xEE\x9A\xB9" => 'black',
        "\xEE\x9A\xBA" => 'black',
        "\xEE\x9A\xBB" => 'red',
        "\xEE\x9A\xBC" => 'red',
        "\xEE\x9A\xBD" => 'red',
        "\xEE\x9A\xBE" => 'red',
        "\xEE\x9A\xBF" => 'red',
        "\xEE\x9B\x80" => 'black',
        "\xEE\x9B\x81" => 'black',
        "\xEE\x9B\x82" => 'black',
        "\xEE\x9B\x83" => 'black',
        "\xEE\x9B\x84" => 'black',
        "\xEE\x9B\x85" => 'black',
        "\xEE\x9B\x86" => 'black',
        "\xEE\x9B\x87" => 'red',
        "\xEE\x9B\x88" => 'red',
        "\xEE\x9B\x89" => 'red',
        "\xEE\x9B\x8A" => 'red',
        "\xEE\x9B\x8B" => 'black',
        "\xEE\x9B\x8C" => 'black',
        "\xEE\x9B\x8D" => 'black',
        "\xEE\x9B\x8E" => 'black',
        "\xEE\x9B\x8F" => 'black',
        "\xEE\x9B\x90" => 'black',
        "\xEE\x9B\x91" => '#FF8000',
        "\xEE\x9B\x92" => '#FF8000',
        "\xEE\x9B\x93" => 'black',
        "\xEE\x9B\x94" => 'black',
        "\xEE\x9B\x95" => 'black',
        "\xEE\x9B\x96" => 'red',
        "\xEE\x9B\x97" => 'red',
        "\xEE\x9B\x98" => 'red',
        "\xEE\x9B\x99" => 'red',
        "\xEE\x9B\x9A" => 'red',
        "\xEE\x9B\x9B" => 'red',
        "\xEE\x9B\x9C" => 'blue',
        "\xEE\x9B\x9D" => 'red',
        "\xEE\x9B\x9E" => 'red',
        "\xEE\x9B\x9F" => 'black',
        "\xEE\x9B\xA0" => 'black',
        "\xEE\x9B\xA1" => 'black',
        "\xEE\x9B\xA2" => 'black',
        "\xEE\x9B\xA3" => 'black',
        "\xEE\x9B\xA4" => 'black',
        "\xEE\x9B\xA5" => 'black',
        "\xEE\x9B\xA6" => 'black',
        "\xEE\x9B\xA7" => 'black',
        "\xEE\x9B\xA8" => 'black',
        "\xEE\x9B\xA9" => 'black',
        "\xEE\x9B\xAA" => 'black',
        "\xEE\x9B\xAB" => 'black',
        "\xEE\x9B\xAC" => 'red',
        "\xEE\x9B\xAD" => 'red',
        "\xEE\x9B\xAE" => 'red',
        "\xEE\x9B\xAF" => 'red',
        "\xEE\x9B\xB0" => 'fuchsia',
        "\xEE\x9B\xB1" => 'red',
        "\xEE\x9B\xB2" => 'blue',
        "\xEE\x9B\xB3" => 'lime',
        "\xEE\x9B\xB4" => 'blue',
        "\xEE\x9B\xB5" => 'red',
        "\xEE\x9B\xB6" => 'red',
        "\xEE\x9B\xB7" => 'red',
        "\xEE\x9B\xB8" => 'fuchsia',
        "\xEE\x9B\xB9" => 'red',
        "\xEE\x9B\xBA" => '#FF8000',
        "\xEE\x9B\xBB" => '#FF8000',
        "\xEE\x9B\xBC" => 'black',
        "\xEE\x9B\xBD" => 'red',
        "\xEE\x9B\xBE" => 'black',
        "\xEE\x9B\xBF" => 'red',
        "\xEE\x9C\x80" => 'blue',
        "\xEE\x9C\x81" => 'blue',
        "\xEE\x9C\x82" => 'red',
        "\xEE\x9C\x83" => 'fuchsia',
        "\xEE\x9C\x84" => 'red',
        "\xEE\x9C\x85" => 'red',
        "\xEE\x9C\x86" => 'black',
        "\xEE\x9C\x87" => 'black',
        "\xEE\x9C\x88" => 'black',
        "\xEE\x9C\x89" => 'black',
        "\xEE\x9C\x8A" => 'black',
        "\xEE\x9C\x8B" => 'red',
        "\xEE\x9C\x8C" => '#FF8000',
        "\xEE\x9C\x8D" => '#FF8000',
        "\xEE\x9C\x8E" => 'blue',
        "\xEE\x9C\x8F" => 'black',
        "\xEE\x9C\x90" => 'red',
        "\xEE\x9C\x91" => 'navy',
        "\xEE\x9C\x92" => 'blue',
        "\xEE\x9C\x93" => '#FF8000',
        "\xEE\x9C\x94" => 'maroon',
        "\xEE\x9C\x95" => 'maroon',
        "\xEE\x9C\x96" => 'black',
        "\xEE\x9C\x97" => 'red',
        "\xEE\x9C\x98" => 'black',
        "\xEE\x9C\x99" => 'lime',
        "\xEE\x9C\x9A" => '#FF8000',
        "\xEE\x9C\x9B" => 'fuchsia',
        "\xEE\x9C\x9C" => 'black',
        "\xEE\x9C\x9D" => 'black',
        "\xEE\x9C\x9E" => 'lime',
        "\xEE\x9C\x9F" => 'black',
        "\xEE\x9C\xA0" => 'lime',
        "\xEE\x9C\xA1" => 'fuchsia',
        "\xEE\x9C\xA2" => 'blue',
        "\xEE\x9C\xA3" => 'blue',
        "\xEE\x9C\xA4" => 'red',
        "\xEE\x9C\xA5" => 'purple',
        "\xEE\x9C\xA6" => 'fuchsia',
        "\xEE\x9C\xA7" => '#FF8000',
        "\xEE\x9C\xA8" => 'red',
        "\xEE\x9C\xA9" => 'fuchsia',
        "\xEE\x9C\xAA" => 'fuchsia',
        "\xEE\x9C\xAB" => 'navy',
        "\xEE\x9C\xAC" => '#FF8000',
        "\xEE\x9C\xAD" => 'blue',
        "\xEE\x9C\xAE" => 'blue',
        "\xEE\x9C\xAF" => 'red',
        "\xEE\x9C\xB0" => 'blue',
        "\xEE\x9C\xB1" => 'black',
        "\xEE\x9C\xB2" => 'black',
        "\xEE\x9C\xB3" => 'black',
        "\xEE\x9C\xB4" => 'red',
        "\xEE\x9C\xB5" => 'lime',
        "\xEE\x9C\xB6" => 'black',
        "\xEE\x9C\xB7" => '#FF8000',
        "\xEE\x9C\xB8" => 'red',
        "\xEE\x9C\xB9" => 'blue',
        "\xEE\x9C\xBA" => 'red',
        "\xEE\x9C\xBB" => 'red',
        "\xEE\x9C\xBC" => 'black',
        "\xEE\x9C\xBD" => 'black',
        "\xEE\x9C\xBE" => 'lime',
        "\xEE\x9C\xBF" => 'blue',
        "\xEE\x9D\x80" => 'blue',
        "\xEE\x9D\x81" => 'lime',
        "\xEE\x9D\x82" => 'red',
        "\xEE\x9D\x83" => 'red',
        "\xEE\x9D\x84" => '#FF8000',
        "\xEE\x9D\x85" => 'red',
        "\xEE\x9D\x86" => 'lime',
        "\xEE\x9D\x87" => 'red',
        "\xEE\x9D\x88" => 'fuchsia',
        "\xEE\x9D\x89" => 'black',
        "\xEE\x9D\x8A" => 'red',
        "\xEE\x9D\x8B" => 'maroon',
        "\xEE\x9D\x8C" => '#FF8000',
        "\xEE\x9D\x8D" => 'maroon',
        "\xEE\x9D\x8E" => 'maroon',
        "\xEE\x9D\x8F" => '#FF8000',
        "\xEE\x9D\x90" => 'navy',
        "\xEE\x9D\x91" => 'blue',
        "\xEE\x9D\x92" => '#FF8000',
        "\xEE\x9D\x93" => '#FF8000',
        "\xEE\x9D\x94" => 'maroon',
        "\xEE\x9D\x95" => '#FF8000',
        "\xEE\x9D\x96" => 'purple',
        "\xEE\x9D\x97" => 'purple',
    );

    /**
     * Callback function called by the filter() method.
     *
     * This function surrounds UTF-8 emoji of NTT docomo with
     * <font color="..."> or <span style="color: ..."> HTML tag.
     *
     * @param  array   $matches
     * @return string
     */
    function _colorDocomoEmoji($matches)
    {
        static $is2G;

        if (isset($is2G) === false) {
            $is2G = preg_match('#^DoCoMo/1.0#', $_SERVER['HTTP_USER_AGENT']);
        }

        $utf8 = $matches[0];

        if (isset($this->_colorTable[$utf8]) === true) {
            $color = $this->_colorTable[$utf8];
            if ($is2G) {
                return '<font color="' . $color . '">' . $utf8 . '</font>';
            } else {
                return '<span style="color:' . $color . '">' . $utf8 . '</span>';
            }
        }
        return $utf8;
    }

    /**
     * Color UTF-8 emojis of NTT docomo.
     *
     * @param  string  $text
     * @return string
     */
    function filter($text)
    {
        $pattern  = '/\xEE[\x98-\x9D][\x80-\xBF]/';
        $callback = array($this, '_colorDocomoEmoji');
        return preg_replace_callback($pattern, $callback, $text);
    }
}
