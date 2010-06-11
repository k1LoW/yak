<?php
  // HTML_Emoji
require_once(APP . DS . 'plugins/yak/vendors/HTML/Emoji.php');
class YakComponent extends Object {
    var $emoji;

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
        $this->emoji->setImageUrl(Router::url('/') . 'yaktai/img/');

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
            $controller->data = $this->recursive_filter($controller->data, 'input');
        }
    }

    /**
     * recursive_filter
     * description
     *
     * @param $data
     * @return
     */
    function recursive_filter($data, $filters = 'input'){
        if(is_array($data)){
            foreach($data as $key => $value){
                $data[$key]= $this->recursive_filter($value, $filters);
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
        return $this->emoji->filter($text, $filters);
    }

    /**
     * generateRedirectUrl
     *
     * @param $url
     * @return $url
     */
    function generateRedirectUrl($url){
        if ($this->isDocomo()) {
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

    /**
     * isDocomo
     *
     * @return
     */
    function isDocomo(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'DoCoMo') !== false) {
            return true;
        }
        return false;
    }
}