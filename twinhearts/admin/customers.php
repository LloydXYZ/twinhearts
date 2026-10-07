<?php require '../config.php';
ahead('Customers'); ?>
<table>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
		<th>Reservations</th>
		<th>Rentals</th>
		<th>Joined</th>
	</tr>
	<?php foreach (q("SELECT u.*,(SELECT COUNT(*) FROM reservations WHERE user_id=u.id) rc,(SELECT COUNT(*) FROM rentals WHERE user_id=u.id) nc FROM users u WHERE role='customer' ORDER BY id DESC") as $u): ?>
		<tr>
			<td>
				<?= e($u['name']) ?>
			</td>
			<td>
				<?= e($u['email']) ?>
			</td>
			<td>
				<?= e($u['phone']) ?>
			</td>
			<td>
				<?= $u['rc'] ?>
			</td>
			<td>
				<?= $u['nc'] ?>
			</td>
			<td>
				<?= $u['created_at'] ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<?php afoot(); ?>