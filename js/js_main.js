var currentPage;
var direction;
var pageCount;

function sendForm() {
	if (!validateUserName()) {
		alert("Указано недопустимое имся пользователя");
		$("#form_main")[0].reset();
		return;
	} else if (!validateEmail()) {
		alert("Указан недопустимый e-mail");
		$("#form_main")[0].reset();
		return;
	} else if (!validatePost()) {
		alert("Текст сообщения пустой");
		$("#form_main")[0].reset();
		return;
	}

	var formData = $('#form_main').serialize();

	$.ajax({
		type: 'POST',
		cache: false,
		url: 'handlers/sendData.php',
		dataType: 'json',
		data: formData,
		complete: function () {
			fillTable(currentPage);
			getPageCount();
			$("#form_main")[0].reset();
		}
	});
}

function fillTable(page) {
	$.ajax({
		type: 'POST',
		url: 'handlers/getData.php',
		data: {
			page: page
		},
		cache: false,
		success: function (html) {
			$('#posts_view').html(html);
		},
		complete: function () {
			makeTableSortable();
		}
	});
}

function getPageCount() {
	$.ajax({
		url: 'handlers/getPageCount.php',
		cache: false,
		success: function (count) {
			pageCount = count;
		}
	});
}

// Проверка всех полей формы
function validateUserName() {
	var userName = document.getElementsByName("user_name")[0].value;

	var regexp = /<[a-z][\s\S]*>/;

	if (userName.length < 4) {
		return false;
	}

	if (regexp.test(userName)) {
		return false;
	}

	return true;
}

function validateEmail() {
	var email = document.getElementsByName("email")[0].value;
	var regexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	return regexp.test(email);
}

function validatePost() {
	var post = document.getElementsByName("post_text")[0].value;
	var regexp = /<[a-z][\s\S]*>/;

	if (post.length == 0) {
		return false;
	}

	if (regexp.test(post)) {
		return false;
	}

	return true;
}

// Сортировка
function makeTableSortable() {
	var th = document.getElementsByTagName("table")[0].tHead;
	var tbody = document.getElementsByTagName("table")[0].getElementsByTagName('tbody').item(0);

	for (var colNumber = 1; colNumber < 4; colNumber++) {
		th.rows[0].cells[colNumber].addEventListener('click', function () {
			sort_table(tbody, $(this).index());
		});
	}
}

function sort_table(tbody, col){
	var rows = tbody.rows, rlen = rows.length, arr = new Array(), i, j, cells, clen;

	for(i = 0; i < rlen; i++){
		cells = rows[i].cells;
		clen = cells.length;
		arr[i] = new Array();
		for(j = 0; j < clen; j++){
			arr[i][j] = cells[j].innerHTML;
		}
	}

	arr.sort(function (a, b) {
		if (a[col] < b[col]) {
			return -1;
		}
		if (a[col] > b[col]) {
			return 1;
		}
		return 0;
	});

	if (direction == -1) {
		arr.reverse();
	}

	for(i = 0; i < rlen; i++){
		arr[i] = "<td>"+arr[i].join("</td><td>")+"</td>";
	}
	tbody.innerHTML = "<tr>"+arr.join("</tr><tr>")+"</tr>";

	direction *= -1;
}

// Управление страницами
function nextPage() {
	currentPage++;

	if (currentPage > pageCount) {
		currentPage--;
		return;
	}

	fillTable(currentPage);
}

function previousPage() {
	currentPage--;

	if (currentPage < 1) {
		currentPage++;
		return;
	}

	fillTable(currentPage);
}

// При загрузке страницы
$(document).ready(function() {
	currentPage = 1;
	direction = 1;
	getPageCount();
	fillTable(currentPage);
});