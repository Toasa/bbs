<?php

  header("Content-Type: application/json");

  // フォーム関連の変数
  $name = ( isset($_POST["name"]) === true) ?$_POST["name"]: "";
  $comment = ( isset($_POST["comment"]) === true ) ?$_POST["comment"]: "";

  // MySQL関連の変数取得
  require_once("setting.php");

  // MySQL処理関数群の取得
  require_once("board_func_box.php");

  // MySQLへ接続後, 配列 [$pdo, $mysql_msg, $mysql_msg_detail] を返す
  // 接続不可の場合, $pdo="" (空文字列)となり返される
  $result = connectToMySQL($dsn, $user, $password, $dbName);

  $pdo = $result[0];
  // MySQL接続の可否を示すメッセージ
  $mysql_msg = $result[1];
  // 接続不可の際、詳細なエラーメッセージ
  $mysql_msg_detail = $result[2];


  if($pdo !== ""){
    // SQLへ接続成功したとき、書込処理の実行 & jsonファイルのecho
    if($name !== "" && $comment !== ""){
      insertNewPost($name, $comment, $pdo);
    }
    echo getJson($pdo, $mysql_msg, $mysql_msg_detail);
  }else{
    // $pdoが空文字列である、つまりSQLへ接続不可のとき、["", エラーメッセージ、　詳細なエラーメッセージ]形式のjsonファイルをechoする
    $a = [$pdo, $mysql_msg, $mysql_msg_detail];
    $json = json_encode( $a , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ;
    echo $json;
  }

?>
