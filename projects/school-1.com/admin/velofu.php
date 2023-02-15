<?php
//session_start();

	// Данная функция предназначена для "одноразовых" сообщений.
	// Если вызвать её со строковым параметром, то она сохранит эту строку в сессии,
	// а если вызвать без параметров, то выведет из сессии сохранённое сообщение и затем удалит его
	// в сессии.
	function flash(?string $message = null)
	{
		if ($message) {
			$_SESSION['flash'] = $message;
		} else {
			if (!empty($_SESSION['flash'])) { ?>
			  <div class="alert alert-danger mb-3">
				  <?=$_SESSION['flash']?>
			  </div>
			<?php }
			unset($_SESSION['flash']);
		}
	}

    // проверка наличия идентификатора в сессии
    function check_auth(): bool
    {
        return !!($_SESSION['user_id'] ?? false);
    }
?>