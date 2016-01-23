<?php

require_once("functions.php");

$errors = array();

if(isset($_POST['submit'])){
    
    $name = $_POST['name'];
    $memo = $_POST['memo'];

    $name = htmlspecialchars($name, ENT_QUOTES);
    $memo = htmlspecialchars($memo, ENT_QUOTES);

    if($name === ''){
        $errors['name'] = 'お名前が入力されていません。';
    }

    if(count($errors) === 0){
        
        $dbh = db_connect();

        $sql = 'INSERT INTO tasks (name, memo, done) VALUES (?, ?, 0)';
        $stmt = $dbh->prepare($sql);

        
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $memo, PDO::PARAM_STR);
        $stmt->execute();

        $dbh = null;

        unset($name, $memo);
    }
}
if(isset($_POST['method']) && ($_POST['method'] === 'put')){
    
    $id = $_POST["id"];
    $id = htmlspecialchars($id, ENT_QUOTES);
    $id = (int)$id;

    $dbh = db_connect();

    $sql = 'UPDATE tasks SET done = 1  WHERE id = ?';
    $stmt = $dbh->prepare($sql);
    
    
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();

    $dbh = null;

}
?>

<!doctype html>
<html lang="ja">
<head>

<meta charset="UTF-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width">

<title>マニュアル｜coffee同好会</title>

<link rel="stylesheet" href="files/css/style.css">
<link rel="shortcut icon" href="files/img/common/favicon.ico">

</head>

<body id="index">
	<div id="all">
		<header>
			<div class="wrap">
				<h1><a href="http://192.168.0.23:8888/coffee-lovers/"><img src="files/images/common/ttl.png" height="40" width="243" alt=""></a></h1>
				<p>このサイトはコーヒー同好会のための投稿サイトです</p>
			</div>
		</header>
		<nav>
			<ul>
				<li><a href="http://192.168.0.23:8888/coffee-lovers/?page_id=6">お知らせ</a></li>
				<li><a href="http://192.168.0.23:8888/coffee-lovers/?page_id=19">コーヒー部について</a></li>
				<li><a href="http://192.168.0.23:8888/coffee-lovers/?page_id=12">メンバーリスト</a></li>
				<li><a href="#">マニュアル</a></li>
			</ul>
		</nav>
		<section id="main" class="wrap">
			<section id="contents">
				<h2 class="ttl"><img src="files/images/ttl.png" alt=""></h2>
				<div class="container">
					<div class="lead">
						<p><span>このページはコーヒー当番のルール表です。</span></p>
						<p>普段の活動で忘れてはいけないことを載せてもいいですし、このサイトの意見で決まった新しいルールなんかも載せてください。</p>
						<p>どんどん書いてコーヒー部の新しいルールをみなさんと共有していきましょう！</p>
					</div>
					<form action="index.php" method="post">
						<ul class="form">
							<li class="textarea"><input type="text" name="name" value="<?php if(isset($name)){print($name);} ?>" placeholder="追加するルールをこちらに入力してください"></li>
							<?php
								if(isset($errors)){
								    print("<ul>");
								    foreach($errors as $value){
								        print("<li>");
								        print($value);
								        print("</li>");
								    }
								    print("</ul>");
								}
							?>
							<li class="btn"><input type="submit" name="submit" value="投稿する"></li>
							<li class="btn"><input type="submit" name="submit" value="リセットする"></li>
						</ul>
					</form>

					<section id="rule">
						<?php

						$dbh = db_connect();

						$sql = 'SELECT id, name, memo FROM tasks WHERE done = 0 ORDER BY id DESC';
						$stmt = $dbh->prepare($sql);
						$stmt->execute();
						$dbh = null;

						while($task = $stmt->fetch(PDO::FETCH_ASSOC)){

						print('<div class="list">');

						    print '<p class="textarea">';
						    print $task["name"];
						    print '</p>';

						    print '<p>';
						    print $task["memo"];
						    print '</p>';

						    print '<p>';
						    print '
						            <form action="index.php" method="post">
						            <input type="hidden" name="method" value="put">
						            <input type="hidden" name="id" value="' . $task['id'] . '">
						            <button class="btn" type="submit">-</button>
						            </form>
						          ' ;
						    print '</p>';

						print('</div>');

						}

						?>
					</section>
				</div>
			</section>
		</section>
		<footer></footer>
	</div>
<script src="files/js/common/base.js"></script>

</body>
</html>