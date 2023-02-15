<?php
	session_start();
	//шапка
	require_once 'html/form.header.php';

	// class velobd
	//require_once 'admin/velobd.php';
	require_once 'admin/velofu.php';
	$dbinfo = require_once 'admin/config.php';

	echo "Адмінка </br>";

	$user = null;

	$pdo = new PDO('mysql:host='.$dbinfo['host'].';port='.$dbinfo['port'].';dbname='.$dbinfo['dbname'].'',
					$dbinfo['login'], $dbinfo['password']);

	if (check_auth()) {
		// Получим данные пользователя по сохранённому идентификатору
		$pdo = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
		$pdo->execute(['id' => $_SESSION['user_id']]);
		$user = $pdo->fetch(PDO::FETCH_ASSOC);
	}

	?>


	<div class="container">
	<div class="row py-5">
		<div class="col-lg-6">

			<?php if ($user) { ?>

			<h1>Welcome back, <?=htmlspecialchars($user['username'])?>!</h1>

			<form class="mt-5" method="post" action="admin/do_logout.php">
				<button type="submit" class="btn btn-primary">Logout</button>
			</form>

			<?php } else { ?>

			<h1 class="mb-5">Registration</h1>

				<?php flash(); ?>

			<form method="post" action="admin/do_register.php">
				<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" id="username" name="username" required>
				</div>
				<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" id="password" name="password" required>
				</div>
				<div class="d-flex justify-content-between">
				<button type="submit" class="btn btn-primary">Register</button>
				<a class="btn btn-outline-primary" href="login.php">Login</a>
				</div>
			</form>

<?php
	} 

	require_once 'html/form.footer.php';

?>