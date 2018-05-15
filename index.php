<?php
include('connect.php');


?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Untitled Document</title>
</head>

<body>
<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'changecolumn':
        //echo '<pre>';
        //print_r($_POST);
        //print_r($_GET);
        $table = $_GET['table'];
        foreach ($_POST['do'] as $field => $ok) {
            if (isset ($_POST['type'][$field]) && $_POST['type'][$field] != "") {
                echo $field . 'Меняем тип на' . $_POST['type'][$field];
                $query = "ALTER TABLE $table CHANGE COLUMN $field $field " . $_POST['type'][$field];
                if ($_POST['old_null'][$field] == 'NO') $query .= ' NOT NULL';
                //echo $query;
                $dbh->query($query);
                //print_r($dbh->errorInfo());
            }
            if (isset ($_POST['name'][$field]) && $_POST['name'][$field] != "") {
                echo $field . 'Меняем имя на' . $_POST['name'][$field];
                $query = "ALTER TABLE $table CHANGE COLUMN $field " . $_POST['name'][$field] . ' ' . $_POST['old_type'][$field];
                if ($_POST['old_null'][$field] == 'NO') $query .= ' NOT NULL';
                //echo $query;
                $dbh->query($query);
                //print_r($dbh->errorInfo());
            }
            if (isset ($_POST['delete'][$field]) && $_POST['delete'][$field] != "") {
                echo $field . 'Удяляем' . $_POST['delete'][$field];
                $query = "ALTER TABLE $table DROP COLUMN $field";
                $dbh->query($query);
                //print_r($dbh->errorInfo());
            }
        }
    case 'table':
        $table = $_GET['table'];
        $allcolumns = $dbh->query("DESCRIBE $table");
        echo '<h3>Все столбцы таблицы ' . $table . ':</h3>
		<form method="post" action="?action=changecolumn&table=' . $table . '">
			<table border="1">
				<tr>
					<th>Название</th>
					<th>Тип</th>
					<th>NULL</th>
					<th>Ключ</th>
					<th>По умолчанию</th>
					<th>Дополнительно</th>
					<th>Изменить тип</th>
					<th>Переименовать</th>
					<th>Удалить</th>
					<th></th>
				</tr>';
        foreach ($allcolumns as $column) {
            echo '
					<tr>
						<td>' . $column['Field'] . '</td>
						<td>' . $column['Type'] . '</td>
						<td>' . $column['Null'] . '</td>
						<td>' . $column['Key'] . '</td>
						<td>' . $column['Default'] . '</td>
						<td>' . $column['Extra'] . '</td>
						<td>
							<select name="type[' . $column['Field'] . ']">
								<option></option>
								<option value="int">int</option>
								<option value="text">text</option>
								<option value="varchar(255)">varchar(255)</option>
							</select>
						</td>
						<td><input type="text" name="name[' . $column['Field'] . ']"></td>
						<td><input type="checkbox" name="delete[' . $column['Field'] . ']" value="Y"></td>
						<td>
						<input type="submit" name="do[' . $column['Field'] . ']" value="OK">
						<input type="hidden" name="old_name[' . $column['Field'] . ']" value="' . $column['Field'] . '">
						<input type="hidden" name="old_type[' . $column['Field'] . ']" value="' . $column['Type'] . '">
						<input type="hidden" name="old_null[' . $column['Field'] . ']" value="' . $column['Null'] . '">
						</td>
					</tr>
				';
        };
        '</table>
		</form>';
        break;
    default:
        $alltables = $dbh->query("SHOW TABLES");
        echo '<h3>Все таблицы базы:</h3>';
        foreach ($alltables as $table) {
            $table = $table[0];
            echo '<p><a href="?action=table&table=' . $table . '">' . $table . '</a></p>';
        };
        break;
}
?>
</body>
</html>
