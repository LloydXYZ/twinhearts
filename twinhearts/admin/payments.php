<?php require '../config.php';
admin();
if ($_POST) {
	$a = $_POST['act'] == 'verify' ? 'verified' : 'rejected';
	$p = q("SELECT * FROM payments WHERE id=?", [$_POST['id']])[0];
	q("UPDATE payments SET status=? WHERE id=?", [$a, $p['id']]);
	notify($p['user_id'], "Your payment of ₱" . number_format($p['amount'], 2) . " was $a.");
	go('payments.php');
}
ahead('Payments'); ?>
<table>
	<tr>
		<th>#</th>
		<th>Customer</th>
		<th>Type</th>
		<th>Ref #</th>
		<th>Amount</th>
		<th>Method</th>
		<th>Reference no.</th>
		<th>Date</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php foreach (q("SELECT p.*,u.name un FROM payments p JOIN users u ON u.id=p.user_id ORDER BY p.id DESC") as $p): ?>
		<tr>
			<td><?= $p['id'] ?></td>
			<td><?= e($p['un']) ?></td>
			<td><?= $p['type'] ?></td>
			<td><?= $p['ref_id'] ?></td>
			<td>₱<?= number_format($p['amount'], 2) ?></td>
			<td><?= e($p['method']) ?></td>
			<td><?= e($p['reference_no']) ?></td>
			<td><?= $p['created_at'] ?></td>
			<td><?= badge($p['status']) ?></td>
			<td>
				<?php if ($p['status'] == 'pending'): ?>
					<form method="post" class="inline"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="sm"
							name="act" value="verify">Verify</button> <button class="sm ghost" name="act" value="reject">Reject</button>
					</form><?php endif; ?>
			</td>
		</tr><?php endforeach; ?>
</table><?php afoot(); ?>