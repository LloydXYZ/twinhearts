<?php require '../config.php';
admin();
if ($_POST) {
	$r = q("SELECT * FROM reservations WHERE id=?", [$_POST['id']])[0];
	$a = ['approve' => 'approved', 'decline' => 'declined', 'complete' => 'completed'][$_POST['act']];
	if ($a == 'approved' && q("SELECT id FROM reservations WHERE event_date=? AND status='approved' AND id<>?", [$r['event_date'], $r['id']]))
		flash('Conflict: another approved reservation exists on that date.');
	else {
		q("UPDATE reservations SET status=? WHERE id=?", [$a, $r['id']]);
		notify($r['user_id'], "Reservation #{$r['id']} is now $a.");
		flash("Reservation $a.");
	}
	go('reservations.php');
}
ahead('Reservations');
$f = $_GET['s'] ?? '';
$sql = "SELECT r.*,u.name un,p.name pn FROM reservations r JOIN users u ON u.id=r.user_id JOIN packages p ON p.id=r.package_id" . ($f ? " WHERE r.status=?" : "") . " ORDER BY event_date";
$rows = q($sql, $f ? [$f] : []); ?>
<p>Filter: <a href="?">All</a> | <a href="?s=pending">Pending</a> | <a href="?s=approved">Approved</a> | <a
		href="?s=declined">Declined</a></p>
<table>
	<tr>
		<th>#</th>
		<th>Customer</th>
		<th>Package</th>
		<th>Event</th>
		<th>Date</th>
		<th>Venue</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php foreach ($rows as $r): ?>
		<tr>
			<td><?= $r['id'] ?></td>
			<td><?= e($r['un']) ?></td>
			<td><?= e($r['pn']) ?></td>
			<td><?= e($r['event_type']) ?></td>
			<td><?= $r['event_date'] ?></td>
			<td><?= e($r['venue']) ?></td>
			<td><?= badge($r['status']) ?></td>
			<td>
				<form method="post" class="inline"><input type="hidden" name="id"
						value="<?= $r['id'] ?>"><?php if ($r['status'] == 'pending'): ?><button class="sm" name="act"
							value="approve">Approve</button> <button class="sm ghost" name="act"
							value="decline">Decline</button><?php elseif ($r['status'] == 'approved'): ?><button class="sm" name="act"
							value="complete">Mark completed</button><?php endif; ?></form>
			</td>
		</tr><?php endforeach; ?>
</table><?php afoot(); ?>