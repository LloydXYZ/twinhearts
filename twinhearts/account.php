<?php require 'config.php';
need();
$u = user()['id'];
if ($_POST) {
	$t = $_POST['type'] == 'rental' ? 'rental' : 'reservation';
	$tbl = $t == 'rental' ? 'rentals' : 'reservations';
	if (q("SELECT id FROM $tbl WHERE id=? AND user_id=? AND status='approved'", [$_POST['ref_id'], $u]) && (float) $_POST['amount'] > 0) {
		q("INSERT INTO payments(user_id,type,ref_id,amount,method,reference_no) VALUES(?,?,?,?,?,?)", [$u, $t, $_POST['ref_id'], $_POST['amount'], $_POST['method'], $_POST['reference_no']]);
		flash('Payment submitted for verification.');
	} else
		flash('Only approved bookings can be paid.');
	go('account.php');
}
head('My Account'); ?>
<h1>Hello, <?= e(user()['name']) ?></h1>
<h2>My reservations</h2>
<table>
	<tr>
		<th>#</th>
		<th>Package</th>
		<th>Date</th>
		<th>Status</th>
		<th>Pay</th>
	</tr>
	<?php foreach (q("SELECT r.*,p.name pn FROM reservations r JOIN packages p ON p.id=r.package_id WHERE r.user_id=? ORDER BY r.id DESC", [$u]) as $r): ?>
		<tr>
			<td><?= $r['id'] ?></td>
			<td><?= e($r['pn']) ?></td>
			<td><?= $r['event_date'] ?></td>
			<td><?= badge($r['status']) ?></td>
			<td><?php if ($r['status'] == 'approved')
				payform('reservation', $r['id']); ?></td>
		</tr><?php endforeach; ?>
</table>
<h2>My rentals</h2>
<table>
	<tr>
		<th>#</th>
		<th>Item</th>
		<th>Qty</th>
		<th>Period</th>
		<th>Status</th>
		<th>Pay</th>
	</tr>
	<?php foreach (q("SELECT r.*,d.name dn FROM rentals r JOIN decorations d ON d.id=r.decoration_id WHERE r.user_id=? ORDER BY r.id DESC", [$u]) as $r): ?>
		<tr>
			<td><?= $r['id'] ?></td>
			<td><?= e($r['dn']) ?></td>
			<td><?= $r['qty'] ?></td>
			<td><?= $r['start_date'] ?> to <?= $r['end_date'] ?></td>
			<td><?= badge($r['status']) ?></td>
			<td><?php if ($r['status'] == 'approved')
				payform('rental', $r['id']); ?></td>
		</tr><?php endforeach; ?>
</table>
<h2>Payments</h2>
<table>
	<tr>
		<th>Type</th>
		<th>Ref #</th>
		<th>Amount</th>
		<th>Method</th>
		<th>Status</th>
	</tr><?php foreach (q("SELECT * FROM payments WHERE user_id=? ORDER BY id DESC", [$u]) as $p): ?>
		<tr>
			<td><?= $p['type'] ?></td>
			<td><?= $p['ref_id'] ?></td>
			<td>₱<?= number_format($p['amount'], 2) ?></td>
			<td><?= e($p['method']) ?></td>
			<td><?= badge($p['status']) ?></td>
		</tr><?php endforeach; ?>
</table>
<h2>Notifications</h2><?php foreach (q("SELECT * FROM notifications WHERE user_id=? ORDER BY id DESC LIMIT 15", [$u]) as $n)
	echo '<p>' . e($n['message']) . ' <small>' . $n['created_at'] . '</small></p>';
foot();
function payform($t, $id)
{ ?>
	<form method="post" style="display:grid;gap:.3rem;min-width:170px"><input type="hidden" name="type"
			value="<?= $t ?>"><input type="hidden" name="ref_id" value="<?= $id ?>"><input name="amount" type="number" step="0.01"
			placeholder="Amount" required><select name="method">
			<option>GCash</option>
			<option>Bank transfer</option>
			<option>Cash</option>
		</select><input name="reference_no" placeholder="Reference no."><button class="sm">Pay</button></form><?php } ?>