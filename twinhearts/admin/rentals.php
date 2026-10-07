<?php require '../config.php';
admin();
if ($_POST) {
	$r = q("SELECT * FROM rentals WHERE id=?", [$_POST['id']])[0];
	$a = ['approve' => 'approved', 'decline' => 'declined', 'return' => 'returned'][$_POST['act']];
	q("UPDATE rentals SET status=? WHERE id=?", [$a, $r['id']]);
	notify($r['user_id'], "Rental #{$r['id']} is now $a.");
	flash("Rental $a.");
	go('rentals.php');
}
ahead('Rental requests'); ?>
<table>
	<tr>
		<th>#</th>
		<th>Customer</th>
		<th>Item</th>
		<th>Qty</th>
		<th>Period</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php foreach (q("SELECT r.*,u.name un,d.name dn FROM rentals r JOIN users u ON u.id=r.user_id JOIN decorations d ON d.id=r.decoration_id ORDER BY r.id DESC") as $r): ?>
		<tr>
			<td><?= $r['id'] ?></td>
			<td><?= e($r['un']) ?></td>
			<td><?= e($r['dn']) ?></td>
			<td><?= $r['qty'] ?></td>
			<td><?= $r['start_date'] ?> to <?= $r['end_date'] ?></td>
			<td><?= badge($r['status']) ?></td>
			<td>
				<form method="post" class="inline"><input type="hidden" name="id"
						value="<?= $r['id'] ?>"><?php if ($r['status'] == 'pending'): ?><button class="sm" name="act"
							value="approve">Approve</button> <button class="sm ghost" name="act"
							value="decline">Decline</button><?php elseif ($r['status'] == 'approved'): ?><button class="sm" name="act"
							value="return">Mark returned</button><?php endif; ?></form>
			</td>
		</tr><?php endforeach; ?>
</table><?php afoot(); ?>