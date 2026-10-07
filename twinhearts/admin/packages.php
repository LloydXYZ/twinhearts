<?php require '../config.php';
admin();
if ($_POST) {
	$a = $_POST['act'];
	if ($a == 'add')
		q("INSERT INTO packages(name,description,price) VALUES(?,?,?)", [$_POST['name'], $_POST['description'], $_POST['price']]);
	if ($a == 'toggle')
		q("UPDATE packages SET active=1-active WHERE id=?", [$_POST['id']]);
	if ($a == 'delete') {
		try {
			q("DELETE FROM packages WHERE id=?", [$_POST['id']]);
		} catch (Exception $x) {
			flash('Package has reservations. Disable it instead.');
		}
	}
	go('packages.php');
}
ahead('Packages'); ?>
<form class="f" method="post" style="margin-bottom:2rem"><input type="hidden" name="act" value="add"><label>Package
		name<input name="name" required></label><label>Contents / description<textarea name="description"
			rows="3"></textarea></label><label>Price<input type="number" step="0.01" name="price" required></label><button>Add
		package</button></form>
<table>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th>Price</th>
		<th>Status</th>
		<th>Actions</th>
	</tr><?php foreach (q("SELECT * FROM packages") as $p): ?>
		<tr>
			<td><?= e($p['name']) ?></td>
			<td><?= e($p['description']) ?></td>
			<td>₱<?= number_format($p['price'], 2) ?></td>
			<td><?= $p['active'] ? badge('available') : badge('declined') ?></td>
			<td>
				<form method="post" class="inline"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="sm ghost"
						name="act" value="toggle"><?= $p['active'] ? 'Disable' : 'Enable' ?></button> <button class="sm ghost" name="act"
						value="delete" onclick="return confirm('Delete this package?')">Delete</button></form>
			</td>
		</tr><?php endforeach; ?>
</table><?php afoot(); ?>