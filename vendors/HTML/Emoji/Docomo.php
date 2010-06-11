<?php

/**
 * Convert emojis for use with NTT docomo mobile phone.
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
 * HTML_Emoji_Docomo
 *
 * Extended HTML_Emoji class for NTT docomo.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2010 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji_Docomo extends HTML_Emoji
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
    var $_regexOthers = '/\xEE[\x80-\x94\xB1-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/';
}
