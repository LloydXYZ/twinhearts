<?php require 'config.php';
if ($_POST) {
	$n = trim($_POST['name']);
	$m = trim($_POST['email']);
	$ph = trim($_POST['phone']);
	$pw = $_POST['password'];
	if (!$n || !filter_var($m, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6)
		flash('Enter a name, valid email, and a password of 6+ characters.');
	elseif (q("SELECT id FROM users WHERE email=?", [$m]))
		flash('That email is already registered.');
	else {
		q("INSERT INTO users(name,email,phone,password) VALUES(?,?,?,?)", [$n, $m, $ph, password_hash($pw, PASSWORD_DEFAULT)]);
		flash('Account created. Please log in.');
		go('login.php');
	}
}
head('Register'); ?>
<h1>Create an account</h1>
<form class="f" method="post"><label>Full name<input name="name" required></label><label>Email<input type="email"
			name="email" required></label><label>Phone<input name="phone"></label><label>Password<input type="password"
			name="password" required minlength="6"></label><button>Create account</button></form><?php foot(); ?>