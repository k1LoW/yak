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
    function afterLayout(){
        parent::afterLayout();
        $view =& ClassRegistry::getObject('view');

        if ($this->emoji->isMobile()) {
            if ($this->emoji->isSjisCarrier()) {
                header("Content-type: application/xhtml+xml; charset=Shift_JIS");
            } else {
                header("Content-type: application/xhtml+xml; charset=UTF-8");
            }
        } else {
            header('Content-Type: text/html; charset=UTF-8');
        }

        if (isset($view->output)) {
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
