<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Title</title>
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
  <link rel="stylesheet" href="<?php echo $root;?>/assets/style/style.<?php echo $css_ext?>">
  <script src="<?php echo $root;?>/assets/js/script.<?php echo $js_ext?>" type="module"></script>
</head>
<body>
  <p>テスト</p>
  <p>背景画像</p>
  <div class="bg"></div>
  <p>静的画像</p>
  <img src="<?php echo get_template_directory_uri();?>/images/static.png" alt="" width="300" height="300">
  <p>JSで画像</p>
  <canvas id="canvas" width="300" height="300"></canvas>
</body>

</html>