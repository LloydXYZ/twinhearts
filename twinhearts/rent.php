<?php require 'config.php';
need();
if ($_POST) {
	[$id, $qty, $s, $en] = [$_POST['decoration_id'], (int) $_POST['qty'], $_POST['start_date'], $_POST['end_date']];
	$dec = q("SELECT * FROM decorations WHERE id=? AND available=1", [$id]);
	if (!$dec || $qty < 1 || $s < date('Y-m-d') || $en < $s)
		flash('Check the item, quantity, and dates.');
	else {
		$used = (int) q("SELECT COALESCE(SUM(qty),0) n FROM rentals WHERE decoration_id=? AND status IN('pending','approved') AND start_date<=? AND end_date>=?", [$id, $en, $s])[0]['n'];
		if ($used + $qty > $dec[0]['quantity'])
			flash('Not enough stock for those dates. Only ' . max(0, $dec[0]['quantity'] - $used) . ' left.');
		else {
			$r = q("INSERT INTO rentals(user_id,decoration_id,qty,start_date,end_date) VALUES(?,?,?,?,?)", [user()['id'], $id, $qty, $s, $en]);
			notify(user()['id'], "Rental request #$r received.");
			flash('Rental request submitted. Status: pending.');
			go('account.php');
		}
	}
}
head('Rent'); ?>
<h1>Request a decoration rental</h1>
<form class="f" method="post">
	<label>Item<select name="decoration_id"><?php foreach (q("SELECT * FROM decorations WHERE available=1") as $d): ?>
				<option value="<?= $d['id'] ?>" <?= ($_GET['d'] ?? 0) == $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?> -
					₱<?= number_format($d['price']) ?>/day</option><?php endforeach; ?>
		</select></label>
	<label>Quantity<input type="number" name="qty" min="1" value="1"></label><label>Start date<input type="date"
			name="start_date" required></label><label>End date<input type="date" name="end_date"
			required></label><button>Submit rental request</button>
</form><?php foot(); ?>