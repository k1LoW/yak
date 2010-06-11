<?php

function getHtml()
{
    $data = array(
        'docomo' => array(
            "&#xE63E;", "&#xE63F;", "&#xE640;", "&#xE641;",
        ),
        'au' => array(
            "&#xF660;", "&#xF665;", "&#xF664;", "&#xF65D;",
        ),
        'SoftBank' => array(
            "&#xE04A;", "&#xE049;", "&#xE04B;", "&#xE048;",
        ),
    );

    $html = '';
    foreach ($data as $carrier => $characters) {
        $html .= '[' . $carrier . ']<br />' . "\n";
        foreach ($characters as $character) {
            $html .= $character;
        }
        $html .= '<br /><br />' . "\n\n";
    }
    return $html;
}


require_once 'HTML/Emoji.php';
$emoji = HTML_Emoji::getInstance();
$emoji->setImageUrl('images/');

$html = getHtml();
$html = $emoji->filter($html, array('HexToUtf8', 'Carrier'));

header('Content-Type: text/html; charset=UTF-8');

echo $html;
