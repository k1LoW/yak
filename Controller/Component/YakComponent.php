<?php
App::uses('YakEmoji', 'Yak.Lib');
App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');

class YakComponent extends Component {
    private $emoji;
    public $settings = array('enabled' => true);

    /**
     * __construct
     *
     * @param ComponentCollection $collection instance for the ComponentCollection
     * @param array $settings Settings to set to the component
     * @return void
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->settings = array_merge($this->settings, $settings);
        parent::__construct($collection, $this->settings);
    }

    /**
     * __call
     *
     * $methodName, $args
     * @returno
     */
    public function __call($methodName, $args){
        return call_user_func(array($this->emoji, $methodName), $args);
    }

    /**
     * initialize
     *
     * @param $controller
     * @return
     */
    public function initialize(Controller $controller, $settings = array()) {
        $this->params = $controller->request->params;
        $this->emoji = YakEmoji::getStaticInstance();
        $this->emoji->setImageUrl(Router::url('/') . 'yak/img/');
        if (!Configure::read('Yak.Session')) {
            Configure::write('Yak.Session', Configure::read('Session'));
        }
        if ($this->settings['enabled']) {
            if ($this->emoji->getCarrier() === 'docomo') {
                Configure::write('Yak.Session.ini',
                                 Set::merge(Configure::read('Yak.Session.ini'),
                                            array('session.use_cookies' => 0,
                                                  'session.use_only_cookies' => 0,
                                                  'session.name' => Configure::read('Session.cookie'),
                                                  'url_rewriter.tags' => 'a=href,area=href,frame=src,input=src,form=fakeentry,fieldset=',
                                                  'session.use_trans_sid' => 1,
                                            )));
                Configure::write('Security.level', 'medium');
                Configure::write('Session', Configure::read('Yak.Session'));
                // auto start
                CakeSession::start();
            }
        }
    }

    /**
     * startup
     *
     * @param $controller
     * @return
     */
    public function startup(Controller $controller) {
        if ($this->settings['enabled']) {
            $controller->helpers[] = 'Yak.Yak';

            if (!empty($controller->request->data)) {
                $controller->request->data = $this->recursiveFilter($controller->request->data, 'input');
            }
        }
    }

    /**
     * recursiveFilter
     *
     * @param $data
     * @param $filter
     * @return
     */
    public function recursiveFilter($data, $filters = 'input'){
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
     *
     * @param
     * @return
     */
    public function filter($text, $filters = 'input') {
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
     * getCarrier
     * Add Android
     *
     */
    public function getCarrier(){
        $result = $this->emoji->getCarrier();
        if ($result === 'pc') {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                if (preg_match('/Android/', $userAgent)) {
                    return 'android';
                }
            }
        }
        return $result;
    }

    /**
     * generateRedirectUrl
     *
     * @param $url
     * @return $url
     */
    public function generateRedirectUrl($url){
        if ($this->emoji->getCarrier() == 'docomo') {
            if (is_array($url)) {
                if (!isset($url['?'])) {
                    $url['?'] = array();
                }
                $url['?'][session_name()] = session_id();
            } else {
                if (strpos($url, '?') === false) {
                    $url .= '?';
                } else {
                    $url .= '&';
                }
                $url .= sprintf("%s=%s", session_name(), urlencode(session_id()));
            }
            return $url;
        }
        return $url;
    }

    /**
     * beforeRender
     *
     */
    public function beforeRender(Controller $controller) {
        if ($this->settings['enabled']) {
            if (empty($this->emoji)) {
                $this->emoji = YakEmoji::getStaticInstance();
            }
            if ($this->emoji->isMobile()) {
                $controller->response->type('xhtml');

                if ($this->emoji->isSjisCarrier()) {
                    $controller->response->charset('Shift_JIS');
                } else {
                    $controller->response->charset('UTF-8');
                }
            } else {
                $controller->response->type('html');
                $controller->response->charset('UTF-8');
            }
        }
    }
}
