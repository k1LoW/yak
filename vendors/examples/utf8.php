<?php

function getHtml()
{
    $data = array(
        'docomo' => array(
            "\xEE\x98\xBE", "\xEE\x98\xBF", "\xEE\x99\x80", "\xEE\x99\x81",
        ),
        'au' => array(
            "\xEE\xBD\xA0", "\xEE\xBD\xA5", "\xEE\xBD\xA4", "\xEE\xBD\x9D",
        ),
        'SoftBank' => array(
            "\xEE\x81\x8A", "\xEE\x81\x89", "\xEE\x81\x8B", "\xEE\x81\x88",
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
$html = $emoji->convertCarrier($html);

header('Content-Type: text/html; charset=UTF-8');

echo $html;
