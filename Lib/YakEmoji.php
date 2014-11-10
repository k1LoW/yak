<?php
if (file_exists(dirname(__FILE__) . '/../vendor/autoload.php')) {
    require_once(dirname(__FILE__) . '/../vendor/autoload.php');
}

class YakEmoji extends HTML_Emoji {

    public static function getStaticInstance($carrier = null) {
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
            $carrier = self::detectCarrier();
        }
        $carrier = strtolower($carrier);
        $carrier = isset($aliases[$carrier]) ? $aliases[$carrier] : 'pc';

        if (isset($instances[$carrier]) === false) {
            $class    = 'HTML_Emoji_' . ucfirst($carrier);
            self::__autoload($class);
            $instance = new $class;
            $instance->_carrier  = $carrier;
            $instances[$carrier] = $instance;
        }

        return $instances[$carrier];
    }

    /**
     * Determine a carrier with Woothee
     *
     * @return string
     */
    private static function detectCarrier() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $classifier = new \Woothee\Classifier;
        $r = $classifier->parse($userAgent);

        // smartphone
        if ($r['category'] === 'smartphone') {
            return 'iPhone';
        }

        // mobilephone
        if ($r['category'] === 'mobilephone') {
            if (preg_match('/^J-PHONE/', $userAgent)) {
                return 'J-PHONE';
            }
            switch ($r['os']) {
            case 'docomo':
                return 'docomo';
            case 'au':
                return 'au';
            case 'SoftBank':
                return 'SoftBank';
            case 'WILLCOM':
                return 'WILLCOM';
            case 'emobile':
                return 'EMOBILE';
            }
            return 'docomo';
        }

        // pc
        return 'PC';
    }

    private static function __autoload($className) {
        $filePath = dirname(__FILE__) . '/../vendor/revulo/' . str_replace('_','/',$className) . '.php';
        //require_once $filePath;
    }

}
