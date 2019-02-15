<?php

// **************************************
// MySQLへの接続
// **************************************
function connectToMySQL($dsn, $user, $password, $dbName) {
  try {
    $pdo = new PDO($dsn, $user, $password);
    // SQL接続エラー発生時に例外を投げる設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysql_msg = "Success to connect a {$dbName}";
    $mysql_msg_detail = "";

  // 例外を受け取り, エラーメッセージを代入する
  }catch(PDOException $e){
    $pdo = "";
    $mysql_msg = "Fail to connect a {$dbName}";
    $mysql_msg_detail = $e->getMessage();
  }

  $result = [$pdo, $mysql_msg, $mysql_msg_detail];

  return $result;
}


// **************************************
// データの書き込み処理
// **************************************
function insertNewPost($name, $comment, $pdo){
  // insert
  $sql1 = "INSERT INTO `board` (`id`, `name`, `comment`) VALUES (NULL, :name, :comment)";
  // nameやcommentを直接使用してsql文を組み立てるのではなく、preparedStatementという仕組み
  // を利用して、sql文を組み立てる。これにより、想定外のSQL文が入ることを回避でき、
  // SQLinjection の根本的な対策となる.
  $stmt1 = $pdo -> prepare($sql1);
  $stmt1->bindParam(":name", $name, PDO::PARAM_STR);
  $stmt1->bindParam(":comment", $comment, PDO::PARAM_STR);
  $stmt1->execute();
}


// **************************************
// [{書込情報}, sql接続成功のメッセージ, 空文字] からなるjsonの取得
// **************************************
function getJson($pdo, $mysql_msg, $mysql_msg_detail){

  $sql2 = "SELECT * FROM board";
  $stmt2 = $pdo->prepare($sql2);
  $stmt2->execute();

  //連想配列
  $result = $stmt2->fetchALL(PDO::FETCH_ASSOC);
  $a = [$result, $mysql_msg, $mysql_msg_detail];
  $json = json_encode( $a , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ;

  return $json;
}

?>
