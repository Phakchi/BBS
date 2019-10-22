<html>
<meta charset="utf-8">
<body>

<h1>Mission_5<br/></h1>
<h2>好きなもの、こと、ひと...ご自由にコメントしてください！<br/></h2>

<?php
// データベース常数
$servername = "localhost";
$username = "tb-210517";
$password = "PH97uPFBjk";
$dbname = "tb210517db";

// 出力制御変数
$showall = 0;
$not_number = 0;
$not_name = 0;
$not_comment = 0;
$delete_number = 0;
$edit_number = 0;
$not_password = 0;
$not_exist = 0;
$not_editable = 0;
$not_identify = 0;
$added = 0;
$deleted = 0;
$edited = 0;

// 編集関連変数
$edit_ckeck = null;
$name_0 = null;
$comment_0 = null;
$password_0 = null;

// ACTIONS
if (isset($_POST['back'])){
	header("Location: mission_5.php");
}
if (isset($_POST['display'])){
	$showall = 1;
}

// FORM ACTIONS
if (isset($_POST['addition'])){
	if (empty($_POST['name'])){
		$not_name = 1;
	}
	if (empty($_POST['comment'])){
		$not_comment = 1;
	}
	if (!empty($_POST['name']) && !empty($_POST['comment'])){

		$gotname = $_POST['name'];
		$gotcomment = $_POST['comment'];
		$gotpassword = @$_POST['password_addition'];

		if (empty($_POST['check'])){

			try {
			    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			    $conn->setAttribute(PDO::ATTR_PERSISTENT, true);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			    $sql = "INSERT INTO myTable (id, name, comment, dtime, password)
			    VALUES (null, '$gotname', '$gotcomment', now(), '$gotpassword')";

			    $conn->exec($sql);
			    $added = 1;
			}
			catch(PDOException $e)
			{
			    echo $sql . "<br>" . $e->getMessage();
			}
		}

		else{

			$gotnumber = $_POST['check'];
			try{
		    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    $sql = "UPDATE myTable SET name='$gotname', comment='$gotcomment', dtime=now() WHERE id='$gotnumber'";

		    $stmt = $conn->prepare($sql);
		    $stmt->execute();

		    $edited = 1;
		    }
			catch(PDOException $e){
			    echo $sql . "<br>" . $e->getMessage();
		    }
		}
		$conn = null;
	}
}

elseif (isset($_POST['delete'])){
	if (empty($_POST['number_d'])){
		$delete_number = 1;
	}
	if (empty($_POST['password_delete'])){
		$not_password = 1;
	}
	if (!empty($_POST['number_d']) && !empty($_POST['password_delete'])){

		if (!is_numeric($_POST['number_d'])){
			$not_number = 1;
		}
		else{
			$gotnumber_d = $_POST['number_d'];
			$gotpassword = @$_POST['password_delete'];

			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$sql = "SELECT * FROM myTable WHERE id='$gotnumber_d'";
			$check = $conn -> query($sql);
		    $numcount = $check -> rowCount();

		    if($numcount==0){
		    	$not_exist = 1;
		    }

		    else{
		    	$sql = $conn -> prepare("SELECT password FROM myTable WHERE id='$gotnumber_d'"); 
		    	$sql -> execute();
		    	$result = $sql->fetch(PDO::FETCH_NUM);
		    	//var_dump($result) ;
		    	//echo $result[0];

		    	if (empty($result[0])){
					$not_editable = 1;
		    	}
		    	else{
		    		
		    		if ($result[0]!=$gotpassword){
			        	$not_identify = 1;
			    	}
			        else {
			        	try {
						    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
						    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						    $sql = "DELETE FROM myTable WHERE id='$gotnumber_d'";

						    $conn->exec($sql);

						    $deleted = 1;
					    }
						catch(PDOException $e){
						    echo $sql . "<br>" . $e->getMessage();
					    }
			    		$conn = null;
			        }
		    	}
			}
		}
	}
}

elseif (isset($_POST['edit'])){
	if (empty($_POST['number_e'])){
		$edit_number = 1;
	}
	if (empty($_POST['password_edit'])){
		$not_password = 1;
	}
	if (!empty($_POST['number_e'])){

		if (is_numeric($_POST['number_e']) && !empty($_POST['password_edit'])){

			if (!is_numeric($_POST['number_e'])){
				$not_number = 1;
			}
			else{
				$gotnumber_e = $_POST['number_e'];
				$gotpassword = @$_POST['password_edit'];

				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$sql = "SELECT * FROM myTable WHERE id='$gotnumber_e'";
				$check = $conn -> query($sql);
			    $numcount = $check -> rowCount();

			    if($numcount==0){
			    	$not_exist = 1;
			    }

			    else{
			    	$sql = $conn -> prepare("SELECT name, comment, password FROM myTable WHERE id='$gotnumber_e'"); 
			    	$sql -> execute();
			    	$result = $sql->fetch(PDO::FETCH_NUM);
			    	/*
			    	var_dump($result) ;
			    	foreach ($result as $r){
			    		echo $r."<br>";
			    	}
			    	echo ($result[0]);
					*/
					
			    	if (empty($result[2])){
						$not_editable = 1;
			    	}
			    	else{
			    		
			    		if ($result[2]!=$gotpassword){
				        	$not_identify = 1;
				    	}
				        else {
				        	$name_0 = $result[0];
							$comment_0 = $result[1];
							$password_0 = "変更できません";
							$edit_ckeck = $gotnumber_e;
							
				        }
				    }
				}
			}
			$conn = null; 			
		}
	}
} 

?>


<!-- 入力フォームなど -->
<h3>投稿</h3>
<form method="POST" action=" " name="a_form"> 
お名前：<input type="text" name="name" value="<?php echo $name_0; ?>"><br>
コメント：<textarea style="width:180px;height:80px;vertical-align:top;"
wrap="virtual" name="comment"><?php echo $comment_0; ?></textarea><br>
パスワード：<input type="text" name="password_addition" value="<?php echo $password_0; ?>"><br>
<input type='hidden' name='check' value="<?php echo $edit_ckeck; ?>"><br/>
<input type="submit" name="addition" value="送信">
</form>

<hr>
<h3>削除</h3>
<form method="POST" action=" " name="d_form"> 
削除対象番号：<input type="text" name="number_d"><br>
パスワード：<input type="text" name="password_delete"><br>
<input type="submit" name="delete" value="送信">
</form>
<p>
<hr>
<h3>編集</h3>
<form method="POST" action=" " name="e_form"> 
編集対象番号：<input type="text" name="number_e"><br>
パスワード：<input type="text" name="password_edit"><br>
<input type="submit" name="edit" value="送信">
</form>
<p>
<hr>

<form method="POST" action=" " name="display"> 
<input type="submit" name="display" value="投稿を表示">
</form>

</body>

</html>

<?php

// 投稿を表示
if ($showall==1){
	echo "今までの投稿：<br>";
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	    $stmt = $conn->prepare("SELECT id, name, comment, dtime FROM myTable"); 
	    $stmt->execute();
	 
	    // 设置结果集为关联数组
	    $results = $stmt->fetchAll();
	    foreach($results as $result) { 
	        echo $result[0]."<br>";
	        echo $result[1]."<br>";
	        echo $result[2]."<br>";
	        echo $result[3]."<br>";
	        echo "<p>";
	    }
	}
	catch(PDOException $e){
	    echo $sql . "<br>" . $e->getMessage();
	}

	$conn = null;
}


// フォームの下に表示されるメッセージ
//
if ($not_name==1){
	echo "<font color=red>お名前を入力してください。</font><p>";
}

//
if ($not_comment==1){
	echo "<font color=red>コメントを入力してください。</font><p>";
}

// 投稿保存
if ($added==1){
	echo "<font color=green>投稿保存しました！</font><p>";
}

//
if ($not_number==1){
	echo "<font color=red>半角数字を入力してください。</font><p>";
}

//
if ($delete_number==1){
	echo "<font color=red>削除投稿番号を入力してください。</font><p>";
}

// 投稿は存在しない
if ($not_exist==1){
    echo "<font color=red>ご指定の投稿はありません。</font><p>";
}

// 投稿は編集できない
if ($not_editable==1){
	echo "<font color=red>ご指定の投稿は編集・削除できません。</font><p>";
}

//
if ($not_identify==1){
	echo "<font color=red>パスワードが違います。</font><p>";
}

// 投稿削除
if ($deleted==1){
	echo "<font color=green>投稿削除成功しました！</font><p>";
}

//
if ($edit_number==1){
	echo "<font color=red>編集投稿番号を入力してください。</font><p>";
}

//
if ($not_password==1){
	echo "<font color=red>パスワードを入力してください。</font><p>";
}

//
if ($edited==1){
	echo "<font color=green>投稿編集成功しました！</font><p>";
}
?>