<?php

require_once 'html/form.header.php';

echo "Адмінка </br>";

require 'admin/velobd.php';
$db = new velobd;

	// Получаем и выводим данные
	echo "<pre>";
	print_r($db->getAll('user1'));

require_once 'html/form.footer.php';

?>