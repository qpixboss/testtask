<?php
if ($_POST) {
	require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

	$currentPage = htmlspecialchars($_POST['page']) - 1;
	$offset = $currentPage * $messageLimit;

	$result = $dbprovider->selectQuery($messageLimit, $offset);

	echo '<table id="main_table" border="1">
		<thead>
			<tr>
				<th>№</th>
				<th>Дата создания</th>
				<th>Имя пользователя</th>
				<th>Электронная почта</th>
				<th>Сообщение</th>
			</tr>
		</thead>';
	echo '<tbody>';
	while ($row = $result->fetch()) {
		echo '<tr><td>' . $row[0] .
			'</td><td>' . $row[1] .
			'</td><td>' . $row[2] .
			'</td><td>' . $row[3] .
			'</td><td>' . $row[4] .
			'</td></tr>';
	}

	echo '</tbody></table>';

	$navigation = '<button onclick="previousPage()">Предыдущая</button><button onclick="nextPage()">Следующая</button>';
	echo $navigation;
}
