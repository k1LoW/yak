<?php
  /**
   * isDocomo
   *
   * @return Boolean
   */
if (!function_exists('isDocomo')) {
    function isDocomo(){
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'DoCoMo') !== false) {
            return true;
        }
        return false;
    }
}

switch (Configure::read('Yak.save')) {
case 'cake':
    if (empty($_SESSION)) {
        if ($iniSet) {
            ini_set('session.use_trans_sid', 0);
            ini_set('url_rewriter.tags', '');
            ini_set('session.serialize_handler', 'php');
            ini_set('session.use_cookies', 1);
            ini_set('session.name', Configure::read('Session.cookie'));
            ini_set('session.cookie_lifetime', $this->cookieLifeTime);
            ini_set('session.cookie_path', $this->path);
            ini_set('session.auto_start', 0);
            ini_set('session.save_path', TMP . 'sessions');
        }
    }
    break;
case 'database':
    if (empty($_SESSION)) {
        if (Configure::read('Session.model') === null) {
            trigger_error(__("You must set the all Configure::write('Session.*') in core.php to use database storage"), E_USER_WARNING);
            $this->_stop();
        }
        if ($iniSet) {
            ini_set('session.use_trans_sid', 0);
            ini_set('url_rewriter.tags', '');
            ini_set('session.save_handler', 'user');
            ini_set('session.serialize_handler', 'php');
            ini_set('session.use_cookies', 1);
            ini_set('session.name', Configure::read('Session.cookie'));
            ini_set('session.cookie_lifetime', $this->cookieLifeTime);
            ini_set('session.cookie_path', $this->path);
            ini_set('session.auto_start', 0);
        }
    }
    session_set_save_handler(
                             array('CakeSession','__open'),
                             array('CakeSession', '__close'),
                             array('CakeSession', '__read'),
                             array('CakeSession', '__write'),
                             array('CakeSession', '__destroy'),
                             array('CakeSession', '__gc')
                             );
    break;
case 'php':
    if (empty($_SESSION)) {
        if ($iniSet) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.name', Configure::read('Session.cookie'));
            ini_set('session.cookie_lifetime', $this->cookieLifeTime);
            ini_set('session.cookie_path', $this->path);
        }
    }
    break;
case 'cache':
    if (empty($_SESSION)) {
        if (!class_exists('Cache')) {
            require LIBS . 'cache.php';
        }
        if ($iniSet) {
            ini_set('session.use_trans_sid', 0);
            ini_set('url_rewriter.tags', '');
            ini_set('session.save_handler', 'user');
            ini_set('session.use_cookies', 1);
            ini_set('session.name', Configure::read('Session.cookie'));
            ini_set('session.cookie_lifetime', $this->cookieLifeTime);
            ini_set('session.cookie_path', $this->path);
        }
    }
    session_set_save_handler(
                             array('CakeSession','__open'),
                             array('CakeSession', '__close'),
                             array('Cache', 'read'),
                             array('Cache', 'write'),
                             array('Cache', 'delete'),
                             array('Cache', 'gc')
                             );
    break;
}

if (isDocomo()) {
    ini_set('session.use_cookies', 0);
    ini_set('session.use_only_cookies', 0);
    ini_set('session.name', Configure::read('Session.cookie'));
    ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src,form=fakeentry,fieldset=');
    ini_set('session.use_trans_sid', 1);
    Configure::write('Security.level', 'medium');
}
if (Configure::read('Yak.save')) {
    Configure::write('Session.save', Configure::read('Yak.save'));
}
