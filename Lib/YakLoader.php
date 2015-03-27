<?php

class YakLoader {

    public static function setIncludePath() {
        foreach (self::dirs() as $dir) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $dir);
        }
    }

    public static function loadClass($class) {
        foreach (self::dirs() as $dir) {
            $filename = "{$dir}/" . str_replace('_', '/', $class) . ".php";
            if (is_file($filename)) {
                require $filename;
                return true;
            }
        }
    }

    public static function dirs() {
    return  array(
        dirname(__FILE__) . DS . "HTML_CSS", // CSS.phpにStrict ErrorがあるためYak内に取り込んで修正
        dirname(__FILE__) . DS . "HTML_CSS_Mobile",
        dirname(__FILE__) . DS . "HTML_CSS_Selector2XPath",
    );
    }
}
