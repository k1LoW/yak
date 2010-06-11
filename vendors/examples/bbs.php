<?php

$url      = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$template = 'bbs.phtml';
$logFile  = 'bbs.log';
$imageUrl = 'images/';

require_once 'HTML/Emoji.php';
$emoji = HTML_Emoji::getInstance();
$emoji->setImageUrl($imageUrl);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = trim($_POST['text']);
    $text = str_replace(array("\0", "\t", "\r", "\n"), '', $text);
    $text = $emoji->filter($text, 'input');

    if ($text != '') {
        $carrier = $emoji->getCarrier();
        $date    = date('Y/m/d H:i');
        $line    = $text . "\t" . $carrier . "\t" . $date . "\n";

        $fp = fopen($logFile, 'a');
        flock($fp, LOCK_EX);
        fputs($fp, $line);
        fclose($fp);

        header('Location: ' . $url);
        exit;
    }
}

$lines = file($logFile);
$lines = array_reverse($lines);

if ($emoji->isSjisCarrier()) {
    header('Content-Type: text/html; charset=Shift_JIS');
} else {
    header('Content-Type: text/html; charset=UTF-8');
}

ob_start();
include $template;
$output = ob_get_contents();
ob_end_clean();
echo $emoji->filter($output, 'output');
