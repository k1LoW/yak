<?php
class YakHelper extends AppHelper {

    var $helpers = array('Html');

    /**
     * __construct
     *
     * @return
     */
    function __construct(){
        $this->emoji = HTML_Emoji::getInstance();
        $this->emoji->setImageUrl($this->url('/') . 'yak/img/');
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
    function afterLayout(){
        parent::afterLayout();
        $view =& ClassRegistry::getObject('view');

        if ($this->emoji->isSjisCarrier()) {
            header('Content-Type: text/html; charset=Shift_JIS');
        } else {
            header('Content-Type: text/html; charset=UTF-8');
        }

        $view->output = $this->emoji->filter($view->output, array('DecToUtf8', 'HexToUtf8', 'output'));
    }

}
