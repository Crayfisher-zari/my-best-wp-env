# 必要環境
- Node.js
- Docker

# 静的制作時
静的なHTMLを作成する場合はNPM Scriptsの`dev`コマンドを起動するとローカルサーバー`localhost:5173`が立ち上がります。このポート番号はWordPress時にも参照するので固定でお願いします。（変更する場合はWordPress側も変更する必要あり）

静的資材は基本`src`フォルダ内で作ります。CSSやJavaScriptは直接`.scss`ファイルや`.ts`を参照すれば、Viteがいい感じにしてくれます。

```html
<link rel="stylesheet" href="/assets/style/style.scss" />
<script src="/assets/js/script.ts" type="module"></script>
```

あとは通常の手順でHTML・CSS・JavaScriptを開発していけばOKです。

# WordPressテーマ開発時
まずDockerを立ち上げてください。静的制作時と同様にNPM Scriptsの`dev`コマンドを起動し、さらに`wp-start`コマンドを実行します。初回のみ色々ダウンロードなどがあるので時間がかかります。`wp-start`が立ち上がると`localhost:8888`にアクセスできるようになります。ここがWordPressのローカル環境になります。

開発時はCSSやJavaScriptはViteのローカルサーバーのものを参照しているので、静的作成時と同じように`src`フォルダ内のファイルを操作してください。終了時は`wp-stop`でDockerのコンテナを停止します。

# 画像の格納先、読み込み方について
`img`タグで読み込む画像は`src/public/images`内に、CSSやjsで読み込む画像は`src/assets/images/`に格納してください。

フォルダ構造
```
└src
  └assets
    └images
      └background.png
      └js.png
  └pubilc
    └images
      └static.png
```
Viteではpublicフォルダはルートとして扱われるため、`/images/static.png`というパスで画像を読み込むことができます。

▼HTML
```html
<img src="/images/static.png" alt="" width="300" height="300" />
```

CSSで画像を読み込む場合は相対パスではなく、`/assets`から始めるパス名にしてください。こちらもビルド時にViteがパスを解決するためです。

▼CSS
```css
background-image: url("/assets/images/background.png");
```
jsで画像ファイルを読み込む場合はViteにビルド時にパス解決されるよう`import`文で読み込んでください。

▼JS
```ts
import imgsrc from "/assets/images/js.png";
// jsから画像を読み込むサンプル
const canvas = document.querySelector<HTMLCanvasElement>("#canvas");
const context = canvas!.getContext("2d");
const image = new Image(300, 300);
image.src = imgsrc;
image.addEventListener("load", () => {
  context?.drawImage(image, 0, 0, 300, 300);
})
```

WordPress開発時にPHPで画像を読み込む場合はテンプレートフォルダ内の画像を参照します。WordPress用ビルド時に静的制作時のpublicの画像はテンプレートフォルダに出力されますが、動的に変更はなされないので画像変更時は都度ビルド、もしくは手動でコピーが必要になります。

```php
<img src="<?php echo get_template_directory_uri();?>/images/static.png" alt="" width="300" height="300" />
```

上記のように静的資材HTMLのコードの`src`の頭に`<?php echo get_template_directory_uri();?>`を付与することでうまく読み込めるようになります。

# 静的資材ビルドについて
静的資材をビルドする場合はNPM Scriptsの`buid`コマンドを実行してください。`dist`フォルダに一式出力されます。

# WordPress用ビルドについて
WordPress用にCSSやJavaScriptをビルドする場合はNPM Scriptsの`buid for WP`コマンドを実行してください。`wordpress/themes/TEMPLATE_NAME/`内に`assets`フォルダと`images`フォルダが出力されます。`assets`フォルダにはビルドした各種CSSやJavaScriptが、`images`にはHTMLから読み込んだ静的な画像が出力されています。

# ビルドファイルでのWordPressの確認方法
ヘッダー部分に下記のデバッグ用のコマンドがあります。

```php
<?php 
  if(WP_DEBUG){
    $root = "http://localhost:5173";
    $css_ext = "scss";
    $js_ext = "ts";
    echo '<script type="module" src="http://localhost:5173/@vite/client"></script>';
  }else{
    $root = get_template_directory_uri();
    $css_ext = "css";
    $js_ext = "js";
  } 
?>
```

この`WP_DEBUG`を`false`に変えることでWordPressがビルドファイルを読み込むようになります。（.wp-env.jsonの設定で`WP_DEBUG`は常に`true`になっています。こちらの値を変更するとDockerのコンテナが再構築され時間がかかるのでオススメしません）

納品時には上記デバッグ用の記述を削除するのが望ましいです。

# WordPressのログイン方法
`http://localhost:8888/wp-admin/`にアクセスし、IDは`admin`パスワードは`password`でログインできます。初回は言語設定が英語なので日本語に変えておくと良いでしょう。

# WordPressコンテンツの同期方法
WordPress内で作成した記事やページ、その他設定などはNPM Scriptsの`wp-contents export`コマンドでバックアップファイルを出力できます。このバックアップファイルをGitなどで管理し、`wp-contents import`でそのバックアップファイルをインポートして開発者間でのWordPressコンテンツを同期できます。あくまで単一のバックアップファイルなので差分管理などはできず、頻繁な更新には向きません。（コンフリクトしてもどちらかのファイルしか採用できません）

