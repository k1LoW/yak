<?php

function getHtml()
{
    $data = array(
        'docomo' => array(
            "&#63647;", "&#63648;", "&#63649;", "&#63650;",
        ),
        'au' => array(
            "&#63072;", "&#63077;", "&#63076;", "&#63069;",
        ),
        'SoftBank' => array(
            "&#57418;", "&#57417;", "&#57419;", "&#57416;",
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
$html = $emoji->filter($html, array('DecToUtf8', 'Carrier'));

header('Content-Type: text/html; charset=UTF-8');

echo $html;
