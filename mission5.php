<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

<?php 
    // ・データベース名：***********
    // ・ユーザー名：***********
    // ・パスワード：***********

    // DB接続設定
    $dsn = 'mysql:dbname=tb230478db;host=localhost';
    $user = 'tb-230478';
    $password = 'mdCmrL9AVU';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS tbmission"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "password varchar(10)"
    .");";
    $stmt = $pdo->query($sql);
    ?>

<?php
    // <!-- 削除機能 -->
    if(!empty($_POST["del"]) && !empty($_POST["pass"])){        // 削除番号とパスワードがあったら
        $delete = $_POST["del"];
        $delpass = $_POST["pass"];
    
        $sql = 'SELECT * FROM tbmission';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            if($delete == $row['id'] && $delpass == $row['password']){
            $sql = "delete from tbmission WHERE id=:id";
            $stmt = $pdo -> prepare($sql);
            $stmt ->bindParam(':id',$delnum,PDO::PARAM_INT);
            $stmt -> execute();
            }
        }
    }

    //  <!-- 編集機能 -->
    if(!empty($_POST["edit"]) && !empty($_POST["pass"])){       // 編集番号とパスワードがあったら
        $edit = $_POST["edit"];
        $editpass = $_POST["pass"];
    
        $sql = 'SELECT * FROM tbmission';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
    
        foreach($results as $row){
        // 番号とパスワードが一致しているか
            if($edit == $row['id'] && $editpass == $row['password']){
            $number = $row['id'];
            $editname = $row['name'];
            $edit_str = $row['comment'];
            }
        }
    }

    //  <!-- 投稿機能 -->
    //  名前・コメント・パスワードがセットされているか
    if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
        //入力情報の受け取り
        $name = $_POST["name"];
        $comment = $_POST["str"];
        $date = date("Y/m/d H:i:s");
        $password = $_POST["pass"];
        // 新規追加 
        if(empty($_POST["number"])){
            $sql = $pdo -> prepare("INSERT INTO tbmission (name,comment,date,password) VALUES (:name, :comment, :date, :password)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $sql -> execute();
        }else{
        //  編集
        $editnum = $_POST["number"];
        $sql = 'SELECT * FROM tbmission';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();        
            foreach($results as $row){
            //編集番号とidが一致しているか
                if($editnum == $row['id']){
                    $sql = "UPDATE tbmission SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    }
?>

<form action="" method="post">
    <input type="text" name="name" value="<?PHP if(isset($editname)){echo $editname;}?>" placeholder="お名前"><br>
    <input type="text" name="str" value="<?PHP if(isset($edit_str)){echo $edit_str;}?>" placeholder="コメント">
    <!--編集判定-->
    <input type="hidden" name="number" value="<?PHP if(isset($number)){echo $number;}?>" ><br>
    <input type="password" name="pass" value="<?PHP if(isset($edit_pass)){echo $edit_pass;}?>" placeholder="パスワード"><br>
    <input type="submit" name="submit"><br><br>
</form>
<!--削除フォーム-->
投稿を削除する↓
<form action="" method="post">
    <input type="number" name="del" value="" placeholder="削除対象番号"><br>
    <input type="password" name="pass" value="" placeholder="パスワード"><br>
    <input type="submit" name="submit" value="削除"><br><br>
</form>
<!--編集フォーム-->
投稿を編集する↓
<form action="" method="post">
    <input type="number" name="edit" value="" placeholder="編集対象番号"><br>
    <input type="password" name="pass" value="" placeholder="パスワード"><br>
    <input type="submit" name="submit" value="編集"><br><br>
</form>

<?php
    $sql = 'SELECT * FROM tbmission';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
		echo $row['date'].'<br>';
        echo "<hr>";
    }
?>
</body>
</html>
