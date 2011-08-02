<?php

/**
 * Convert emojis of mobile phones.
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
 * HTML_Emoji
 *
 * Convert emojis of mobile phones.
 *
 * @category   HTML
 * @package    HTML_Emoji
 * @author     revulo <revulon@gmail.com>
 * @copyright  2009-2011 revulo
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    Release: 0.8
 * @link       http://libemoji.com/html_emoji
 */
class HTML_Emoji
{
    /**
     * Whether the user agent is neither smartphone nor PC
     * @var    boolean
     */
    var $mobile;

    /**
     * Whether UTF-8 is a recommended encoding for the carrier or not
     * @var    boolean
     */
    var $utf8;

    /**
     * Carrier name
     * @var    string
     */
    var $_carrier = '';

    /**
     * Regular expression of UTF-8 emojis
     * @var    string
     */
    var $_regexEmoji = '/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/';

    /**
     * Regular expression of UTF-8 emojis of other carriers
     * @var    string
     */
    var $_regexOthers = '/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/';

    /**
     * Name of emoji translation table
     * @var    string
     */
    var $_conversionRule = 'default';

    /**
     * Emoji translation table
     * @var    array
     */
    var $_translationTable;

    /**
     * Escape output of convertCarrier() method
     * @var    boolean
     */
    var $_escape = true;

    /**
     * Use half-width katakana
     * @var    boolean
     */
    var $_halfwidthKatakana = false;

    /**
     * Return an HTML_Emoji instance for that carrier.
     *
     * @param  string  $carrier
     * @return HTML_Emoji
     */
    function &getInstance($carrier = null)
    {
        static $instances = array();

        $aliases = array(
            'docomo'   => 'docomo',
            'i-mode'   => 'docomo',
            'imode'    => 'docomo',
            'au'       => 'au',
            'kddi'     => 'au',
            'ezweb'    => 'au',
            'aumail'   => 'aumail',
            'softbank' => 'softbank',
            'disney'   => 'softbank',
            'vodafone' => 'softbank',
            'iphone'   => 'iphone',
            'j-phone'  => 'jphone',
            'jphone'   => 'jphone',
            'willcom'  => 'docomo',
            'emobile'  => 'docomo',
        );

        if (isset($carrier) === false) {
            $carrier = HTML_Emoji::_detectCarrier();
        }
        $carrier = strtolower($carrier);
        $carrier = isset($aliases[$carrier]) ? $aliases[$carrier] : 'pc';

        if (isset($instances[$carrier]) === false) {
            $class    = 'HTML_Emoji_' . ucfirst($carrier);
            $dirname  = substr(__FILE__, 0, -4);
            $filename = $dirname . '/' . ucfirst($carrier) . '.php';

            require_once $filename;
            $instance = new $class;
            $instance->_carrier  = $carrier;
            $instances[$carrier] = $instance;
        }

        return $instances[$carrier];
    }

    /**
     * Set the base URL to image files.
     *
     * @param  string  $url
     */
    function setImageUrl($url)
    {
    }

    /**
     * Set the name of emoji translation table.
     *
     * @param  string  $name
     */
    function setConversionRule($name)
    {
        $this->_conversionRule = $name;

        if (isset($this->_translationTable) === true) {
            $this->_initTranslationTable();
        }
    }

    /**
     * Disable output escaping.
     *
     * @param  boolean $flag
     */
    function disableEscaping($flag = true)
    {
        $this->_escape = !$flag;
    }

    /**
     * Use half-width katakana.
     *
     * @param  boolean $flag
     */
    function useHalfwidthKatakana($flag = true)
    {
        $this->_halfwidthKatakana = (boolean)$flag;
    }

    /**
     * Convert the text using user-defined filters.
     *
     * @param  string       $text
     * @param  string|array $filters
     * @return string
     */
    function filter($text, $filters)
    {
        static $instances = array();

        if (is_array($filters) === false) {
            $filters = (array)$filters;
        }

        foreach ($filters as $filter) {
            if (preg_match('/^[A-Za-z0-9._-]+$/', $filter) === 0) {
                continue;
            }
            $filter = ucfirst($filter);

            if (isset($instances[$filter]) === false) {
                $class    = 'HTML_Emoji_Filter_' . $filter;
                $dirname  = substr(__FILE__, 0, -4) . '/Filter';
                $filename = $dirname . '/' . $filter . '.php';

                require_once $filename;
                $instances[$filter] = new $class($this);
            }
            $text = $instances[$filter]->filter($text);
        }

        return $text;
    }

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
        return mb_convert_encoding($text, $to, $from);
    }

    /**
     * Convert UTF-8 emojis of other carriers.
     *
     * @param  string  $text
     * @return string
     */
    function convertCarrier($text)
    {
        $pattern  = $this->_regexOthers;
        $callback = array($this, '_convertCharacter');
        return preg_replace_callback($pattern, $callback, $text);
    }

    /**
     * Check whether the text contains a UTF-8 emoji.
     *
     * @param  string  $text
     * @return boolean
     */
    function hasEmoji($text)
    {
        return (boolean)preg_match($this->_regexEmoji, $text);
    }

    /**
     * Remove UTF-8 emojis in the text.
     *
     * @param  string  $text
     * @return string
     */
    function removeEmoji($text)
    {
        return preg_replace($this->_regexEmoji, '', $text);
    }

    /**
     * Return true if the user agent is neither smartphone nor PC.
     *
     * @return boolean
     */
    function isMobile()
    {
        return (boolean)$this->mobile;
    }

    /**
     * Return true if Shift_JIS is a recommended encoding for the carrier.
     *
     * @return boolean
     */
    function isSjisCarrier()
    {
        return !$this->utf8;
    }

    /**
     * Return true if UTF-8 is a recommended encoding for the carrier.
     *
     * @return boolean
     */
    function isUtf8Carrier()
    {
        return (boolean)$this->utf8;
    }

    /**
     * Return the carrier name.
     *
     * 'docomo', 'au', 'aumail', 'softbank', 'jphone', 'iphone' or 'pc'.
     *
     * @return string
     */
    function getCarrier()
    {
        return $this->_carrier;
    }

    /**
     * Return a regular expression of UTF-8 emojis.
     *
     * @return string
     */
    function getRegexEmoji()
    {
        return $this->_regexEmoji;
    }

    /**
     * Return a regular expression of UTF-8 emojis of other carriers.
     *
     * @return string
     */
    function getRegexOthers()
    {
        return $this->_regexOthers;
    }

    /**
     * Wrapper function for mb_decode_numericentity().
     *
     * mb_decode_numericentity() function has a bug that it deletes a
     * trailing ampersand if the input string ends with an odd number of
     * ampersands.
     * @link http://bugs.php.net/bug.php?id=40685
     *
     * @param  string  $str
     * @param  array   $convmap
     * @param  string  $encoding
     * @return string
     */
    function decodeNumericentity($str, $convmap, $encoding)
    {
        $length = strlen($str);

        if ($str[$length - 1] === '&') {
            $str[$length - 1] = ' ';
            $str = mb_decode_numericentity($str, $convmap, $encoding);
            $length = strlen($str);
            $str[$length - 1] = '&';
            return $str;
        } else {
            return mb_decode_numericentity($str, $convmap, $encoding);
        }
    }

    /**
     * Determine a carrier from HTTP_USER_AGENT string.
     *
     * @return string
     */
    function _detectCarrier()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/^DoCoMo/', $userAgent)) {
                return 'docomo';
            } else if (preg_match('/^KDDI-/', $userAgent)) {
                return 'au';
            } else if (preg_match('/^(?:SoftBank|Vodafone|MOT-)/', $userAgent)) {
                return 'SoftBank';
            } else if (preg_match('#^Mozilla/3\.0\((?:WILLCOM|DDIPOCKET);#', $userAgent)) {
                return 'WILLCOM';
            } else if (preg_match('/^(?:emobile|Huawei|IAC)/', $userAgent)) {
                return 'EMOBILE';
            } else if (preg_match('#^Mozilla/5\.0 \((?:iPhone|iPod|iPad);#', $userAgent)) {
                if (preg_match('/OS ([\d_]+)/', $userAgent, $matches)) {
                    if (version_compare($matches[1], '2.2', '>=')) {
                        return 'iPhone';
                    }
                }
            } else if (preg_match('/^J-PHONE/', $userAgent)) {
                return 'J-PHONE';
            }
        }
        if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE'])) {
            if ($_SERVER['HTTP_X_OPERAMINI_PHONE'] === 'Apple # iPhone') {
                return 'iPhone';
            }
        }
        return 'PC';
    }

    /**
     * Normalize character encoding.
     *
     * @param  string  $encoding
     * @return string
     */
    function _normalizeEncoding($encoding)
    {
        $encoding = strtoupper($encoding);

        if ($encoding === 'SJIS') {
            $encoding = 'SJIS-WIN';
        } else if ($encoding === 'JIS' || $encoding === 'ISO-2022-JP') {
            if (version_compare(PHP_VERSION, '5.2.1', '>=')) {
                $encoding = 'ISO-2022-JP-MS';
            } else {
                $encoding = 'JIS';
            }
        }
        return $encoding;
    }

    /**
     * Callback function called by convertCarrier() method.
     *
     * This function converts an emoji to its alternative.
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
            $alt = $this->_translationTable[$utf8];

            if ($alt !== '') {
                $char = $alt[0];
                if ($char !== "\xEE" && $char !== "\xEF") {
                    if ($this->_escape === true) {
                        $alt = htmlspecialchars($alt, ENT_QUOTES);
                    }
                    if ($char === '[' && $this->_halfwidthKatakana === false) {
                        $alt = mb_convert_kana($alt, 'KV', 'UTF-8');
                    }
                }
            }

            return $alt;
        } else {
            return $utf8;
        }
    }

    /**
     * Read emoji translation table.
     */
    function _initTranslationTable()
    {
        $parents = array(
            'aumail' => 'au',
            'iphone' => 'softbank',
            'jphone' => 'softbank',
        );

        $carrier = $this->getCarrier();
        $carrier = isset($parents[$carrier]) ? $parents[$carrier] : $carrier;

        if ($carrier === 'pc') {
            $filename = 'Default.php';
        } else {
            $filename = ucfirst(strtolower($this->_conversionRule)) . '.php';
        }

        $dirname = substr(__FILE__, 0, -4) . '/' . ucfirst($carrier);
        $this->_translationTable = include $dirname . '/' . $filename;
    }
}
