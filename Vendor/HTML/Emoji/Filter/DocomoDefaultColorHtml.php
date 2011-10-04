<?php

/**
 * Display UTF-8 emojis of NTT docomo in the default color.
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

require_once dirname(__FILE__) . '/DocomoDefaultColor.php';

/**
 * HTML_Emoji_Filter_DocomoDefaultColorHtml
 *
 * In NTT docomo mobile phones, emoji is treated as a text character and
 * its color is influenced by style settings. This filter forces emojis
 * to be displayed in the default color.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Filter_DocomoDefaultColorHtml extends HTML_Emoji_Filter_DocomoDefaultColor
{
    /**
     * Callback function called by the filter() method.
     *
     * This function surrounds a UTF-8 emoji of NTT docomo with
     * <font color="..."> and </font> tags.
     *
     * @param  array   $matches
     * @return string
     */
    function _colorDocomoEmoji($matches)
    {
        $utf8 = $matches[0];

        if (isset($this->_colorTable[$utf8]) === true) {
            $color = $this->_colorTable[$utf8];
            return '<font color="' . $color . '">' . $utf8 . '</font>';
        }
        return $utf8;
    }
}
