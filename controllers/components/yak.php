<?php
  // HTML_Emoji
require_once(APP . DS . 'plugins/yak/vendors/HTML/Emoji.php');
class YakComponent extends Object {
    var $emoji;

    /**
     * __call
     *
     * $methodName, $args
     * @return
     */
    function __call($methodName, $args){
        return call_user_func(array($this->emoji, $methodName), $args);
    }

    /**
     * initialize
     *
     * @param &$controller
     * @param $settings
     * @return
     */
    function initialize(&$controller, $settings = array()) {
        $this->params = $controller->params;

        $this->emoji = HTML_Emoji::getInstance();
        $this->emoji->setImageUrl(Router::url('/') . 'yak/img/');

        if (!Configure::read('Yak.save')) {
            Configure::write('Yak.save', Configure::read('Session.save'));
        }

        Configure::write('Session.save', '../plugins/yak/config/session');
    }

    /**
     * startup
     *
     * @param &$controller
     * @return
     */
    function startup(&$controller) {
        $controller->helpers[] = 'Yak.Yak';

        if (!empty($controller->data)) {
            $controller->data = $this->recursiveFilter($controller->data, 'input');
        }
    }

    /**
     * recursiveFilter
     * description
     *
     * @param $data
     * @return
     */
    function recursiveFilter($data, $filters = 'input'){
        if(is_array($data)){
            foreach($data as $key => $value){
                $data[$key]= $this->recursiveFilter($value, $filters);
            }
        }else{
            $data = $this->filter($data, $filters);
        }
        return $data;
    }

    /**
     * filter
     * description
     *
     * @param
     * @return
     */
    function filter($text, $filters = 'input') {
        if ($filters === 'input') {
            if ($this->emoji->isSjisCarrier()) {
                if (mb_detect_encoding($text) === 'UTF-8' || mb_detect_encoding($text) === 'ASCII') {
                    return $this->emoji->filter($text, array('HexToUtf8', 'DecToUtf8'));
                }
                return $this->emoji->filter($text, 'input');
            } else {
                // UTF-8
                return $this->emoji->filter($text, 'input');
            }
        }
        return $this->emoji->filter($text, $filters);
    }

    /**
     * generateRedirectUrl
     *
     * @param $url
     * @return $url
     */
    function generateRedirectUrl($url){
        if ($this->emoji->getCarrier() == 'docomo') {
            if(is_array($url)) {
                if(!isset($url['?'])) {
                    $url['?'] = array();
                }
                $url['?'][session_name()] = session_id();
            }else {
                if(strpos($url, '?') === false) {
                    $url .= '?';
                }else {
                    $url .= '&';
                }
                $url .= sprintf("%s=%s", session_name(), urlencode(session_id()));
            }
            return $url;
        }
        return $url;
    }
}