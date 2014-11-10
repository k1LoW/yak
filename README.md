# Yak: Yet Another Ktai plugin for CakePHP

![image](yak.png)

## Features

### レンダリングエンジンにHTML_Emojiを採用

絵文字を含むビューのレンダリングエンジンに変換処理が高速な "HTML_Emoji":http://libemoji.com/ を採用しています。
Yakは "HTML_Emoji":http://libemoji.com/ のCakePHP用ラッパーであると言っても過言ではありません。

### シンプル

機能は絵文字を含めた表示とセッション管理のみです。それ以外も必要になったら作ります。
ソースコードはUTF-8固定、セッション使用固定、それ以外も "HTML_Emoji":http://libemoji.com/ の制約に沿うことで、非常にシンプルな使用ができることを目指しています。
*"Do It Yourself!"* と言いたいところですが、本当のところはわかりません。

### CakePHPに特化

app/pluginsにyakを設置し、app_contoller.phpに以下のように記述するだけ設定完了です。

```
class AppController extends Controller {
    var $components = array('Yak.Yak');

    function redirect($url, $status = null, $exit = true){
        parent::redirect($this->Yak->generateRedirectUrl($url), $status, $exit);
    }
}
```

POSTした絵文字をDBに保存したいときにはphp.iniや.htaccessで @mbstring.http_input=pass@ としてください (そうでない場合は特にDocomo以外の場合絵文字部分が文字化けします)
また、絵文字表示に使用する "画像ファイル":http://libemoji.com/download はコミットしていませんので、こちらもapp/Plugins/Yak/webroot/img/に展開してください。

## Requirements

* PHP >= 5.2.6
* CakePHP >= 2.0

## FAQ

### 動かないんだけど

動かないかもしれません。ちなみに動かないのは "HTML_Emoji":http://libemoji.com/ のせいではありません。

## Lisence

### HTML_Emoji

Author : revulo <revulon@gmail.com>
Copyright : 2009- revulo
License : http://www.opensource.org/licenses/mit-license.php  MIT License
Version : Release: 0.8.3
Link : http://libemoji.com/html_emoji

### Yak

Author : Kenichirou Oyama 
Copyright : 2010- 101000code/101000LAB
License : http://www.opensource.org/licenses/mit-license.php  MIT License
