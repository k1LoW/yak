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

        if (!is_null($view)) {
            if (empty($this->data) || $this->emoji->isMobile()) {
                $view->output = $this->emoji->filter($view->output, array('DecToUtf8', 'HexToUtf8', 'output'));
            } else {
                // for PC form
                $outputArray = preg_split('/(value ?= ?[\'"][^"]+[\'"])|(<textarea[^>]+>[^<]+<\/textarea>)/',  $view->output, null, PREG_SPLIT_DELIM_CAPTURE);
                $output = '';
                foreach ($outputArray as $key => $value) {
                    if (!preg_match('/value ?= ?[\'"]([^"]+)[\'"]|<textarea[^>]+>([^<]+)<\/textarea>/',  $value)) {
                        $output .= $this->emoji->filter($value, array('DecToUtf8', 'HexToUtf8', 'output'));
                    } else {
                        $output .= $value;
                    }
                }
                $view->output = $output;
            }
        }
    }

  }
