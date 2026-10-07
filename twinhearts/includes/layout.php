<?php
function head($t)
{
	$u = user(); ?>
	<!doctype html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title><?= e($t) ?> | Twin Hearts</title>
		<link
			href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;700&family=Karla:wght@400;600&display=swap"
			rel="stylesheet">
		<link rel="stylesheet" href="<?= BASE ?>assets/style.css">
	</head>

	<body>
		<header class="top"><a class="logo" href="<?= BASE ?>">Twin Hearts</a>
			<nav>
				<a href="<?= BASE ?>services.php">Services</a><a href="<?= BASE ?>decorations.php">Decorations</a><a
					href="<?= BASE ?>reserve.php">Reserve</a><a href="<?= BASE ?>rent.php">Rent</a>
				<?php if ($u): ?><a href="<?= BASE ?>account.php">My Account</a><?php if ($u['role'] === 'admin'): ?><a
							href="<?= BASE ?>admin/">Admin</a><?php endif; ?><a href="<?= BASE ?>logout.php">Log out</a>
				<?php else: ?><a href="<?= BASE ?>login.php">Log in</a><a class="btn sm"
						href="<?= BASE ?>register.php">Register</a><?php endif; ?>
			</nav>
		</header>
		<main><?php if (!empty($_SESSION['f'])) {
			echo '<p class="flash">' . e($_SESSION['f']) . '</p>';
			unset($_SESSION['f']);
		}
}
function foot()
{ ?></main>
		<footer>Twin Hearts Wedding and Events &middot; Reservations and decoration rentals</footer>
	</body>

	</html>
<?php }
function ahead($t)
{
	admin();
	head($t);
	$l = ['index' => 'Dashboard', 'reservations' => 'Reservations', 'rentals' => 'Rental requests', 'decorations' => 'Decorations', 'packages' => 'Packages', 'payments' => 'Payments', 'customers' => 'Customers'];
	$c = basename($_SERVER['SCRIPT_NAME'], '.php'); ?>
	<div class="adm">
		<aside><?php foreach ($l as $k => $v): ?><a class="<?= $c === $k ? 'on' : '' ?>"
					href="<?= BASE ?>admin/<?= $k ?>.php"><?= $v ?></a><?php endforeach; ?></aside>
		<section>
			<h1><?= e($t) ?></h1>
		<?php }
function afoot()
{
	echo '</section></div>';
	foot();
}
function badge($s)
{
	return '<span class="b ' . e($s) . '">' . e($s) . '</span>';
}