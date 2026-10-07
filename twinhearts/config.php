<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'twinhearts');
if ($db->connect_error)
	die('Database error. Import database.sql in phpMyAdmin first.');
define('BASE', '/twinhearts/');
function q($sql, $p = [])
{
	global $db;
	$s = $db->prepare($sql);
	if ($p)
		$s->bind_param(str_repeat('s', count($p)), ...$p);
	$s->execute();
	$r = $s->get_result();
	return $r ? $r->fetch_all(MYSQLI_ASSOC) : $s->insert_id;
}
function e($s)
{
	return htmlspecialchars((string) $s, ENT_QUOTES);
}
function user()
{
	return $_SESSION['u'] ?? null;
}
function need()
{
	if (!user()) {
		header('Location:' . BASE . 'login.php');
		exit;
	}
}
function admin()
{
	need();
	if (user()['role'] !== 'admin') {
		header('Location:' . BASE);
		exit;
	}
}
function flash($m)
{
	$_SESSION['f'] = $m;
}
function notify($uid, $m)
{
	q("INSERT INTO notifications(user_id,message) VALUES(?,?)", [$uid, $m]);
}
function go($u)
{
	header('Location:' . $u);
	exit;
}
// seed default admin on first run
if (!q("SELECT id FROM users WHERE role='admin'"))
	q("INSERT INTO users(name,email,password,role) VALUES('Admin','admin@twinhearts.com',?,'admin')", [password_hash('admin123', PASSWORD_DEFAULT)]);
require __DIR__ . '/includes/layout.php';