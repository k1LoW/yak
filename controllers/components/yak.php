<?php
// HTML_Emoji
App::import('Vendor', 'Yak.HTML_Emoji', array('file' => 'vendors' . DS . 'HTML' . DS . 'Emoji.php'));
class YakComponent extends Object {
    var $emoji;
    var $settings = array('enabled' => true);

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
        $this->settings = am($this->settings, $settings);
        $this->params = $controller->params;

        $this->emoji = HTML_Emoji::getInstance();
        $this->emoji->setImageUrl(Router::url('/') . 'yak/img/');

        if (!Configure::read('Yak.save')) {
            Configure::write('Yak.save', Configure::read('Session.save'));
        }

        if ($this->settings['enabled']) {
            $path = '../plugins/yak/config/session';
            do{
                $config = CONFIGS . $path . '.php';
                if (is_file($config)) {
                    break ;
                }
                $path = '../../plugins/yak/config/session';
                $config = CONFIGS . $path . '.php';
                if (is_file($config)) {
                    break ;
                }
                trigger_error(__("Can't find yak session file.", true), E_USER_ERROR);
            }while(false);
            Configure::write('Session.save', $path);
        }
    }

    /**
     * startup
     *
     * @param &$controller
     * @return
     */
    function startup(&$controller) {
        if ($this->settings['enabled']) {
            $controller->helpers[] = 'Yak.Yak';

            if (!empty($controller->data)) {
                $controller->data = $this->recursiveFilter($controller->data, 'input');
            }
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
                if (!empty($value)) {
                    $data[$key]= $this->recursiveFilter($value, $filters);
                }
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