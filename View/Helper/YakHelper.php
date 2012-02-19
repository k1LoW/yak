<?php
App::uses('AppHelper', 'View/Helper');

class YakHelper extends AppHelper {

    var $helpers = array('Html');

    /**
     * __construct
     *
     * @return
     */
    function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);
        $this->emoji = HTML_Emoji::getInstance();
        $this->emoji->setImageUrl($this->url('/') . 'yak/img/');
    }

    /**
     * __call
     *
     * @param $methodName, $args
     * @return
     */
    function __call($methodName, $args){
        return call_user_func_array(array($this->emoji, $methodName), $args);
    }

    /**
     * charset
     *
     * @return
     */
    function charset(){
        if ($this->emoji->isSjisCarrier()) {
            return $this->Html->charset('Shift_JIS');
        } else {
            return $this->Html->charset('UTF-8');
        }
    }

    /**
     * afterLayout
     *
     * @return
     */
    function afterLayout($layoutFile){
        parent::afterLayout($layoutFile);

        if (isset($this->_View->output)) {
            if (empty($this->request->data) || $this->emoji->isMobile()) {
                $this->_View->output = $this->emoji->filter($this->_View->output, array('DecToUtf8', 'HexToUtf8', 'output'));
            } else {
                // for PC form
                $outputArray = preg_split('/(value ?= ?[\'"][^"]+[\'"])|(<textarea[^>]+>[^<]+<\/textarea>)/',  $this->_View->output, null, PREG_SPLIT_DELIM_CAPTURE);
                $output = '';
                foreach ($outputArray as $key => $value) {
                    if (!preg_match('/value ?= ?[\'"]([^"]+)[\'"]|<textarea[^>]+>([^<]+)<\/textarea>/',  $value)) {
                        $output .= $this->emoji->filter($value, array('DecToUtf8', 'HexToUtf8', 'output'));
                    } else {
                        $output .= $value;
                    }
                }
                $this->_View->output = $output;
            }
        }
    }

}
