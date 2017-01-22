<?php
if ($_POST) {
	$user = htmlspecialchars($_POST['user_name']);
	$email = htmlspecialchars($_POST['email']);
	$postData = htmlspecialchars($_POST['post_text']);

	$json = array();

	if (!$user or !$email or !$postData) {
		$json['error'] = 'Не все поля заполнены!';
		echo json_encode($json);
		die();
	}

	if (!preg_match("/^[a-zA-Z0-9 ]*$/",$user)) {
		$json['error'] = 'Ошибка в имени пользователя!';
		echo json_encode($json);
		die();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$json['error'] = 'Ошибка в E-mail!';
		echo json_encode($json);
		die();
	}

	if (strlen($postData) == 0) {
		$json['error'] = 'Сообщение пустое!';
		echo json_encode($json);
		die();
	}

	require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
	$result = $dbprovider->insertQuery(array($user, $email, $postData));
	if (!$result) {
		$json['error'] = 'Не удалось добавить в базу!';
		echo json_encode($json);
		die();
	}
}