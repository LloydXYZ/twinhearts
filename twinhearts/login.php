<?php require 'config.php';
if ($_POST) {
	$r = q("SELECT * FROM users WHERE email=?", [trim($_POST['email'])]);
	if ($r && password_verify($_POST['password'], $r[0]['password'])) {
		session_regenerate_id(true);
		unset($r[0]['password']);
		$_SESSION['u'] = $r[0];
		go($r[0]['role'] === 'admin' ? 'admin/' : 'account.php');
	}
	flash('Email or password is incorrect.');
}
head('Log in'); ?>
<h1>Log in</h1>
<form class="f" method="post"><label>Email<input type="email" name="email" required></label><label>Password<input
			type="password" name="password" required></label><button>Log in</button>
	<p>No account? <a href="register.php">Register</a></p>
</form><?php foot(); ?>