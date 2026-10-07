<?php require '../config.php';
admin();
if ($_POST) {
	$a = $_POST['act'];
	if ($a == 'add') {
		$img = '';
		if (!empty($_FILES['image']['name']) && preg_match('/\.(jpe?g|png|webp)$/i', $_FILES['image']['name'], $m)) {
			$img = uniqid() . '.' . strtolower($m[1]);
			move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $img);
		}
		q("INSERT INTO decorations(name,description,price,quantity,image) VALUES(?,?,?,?,?)", [$_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity'], $img]);
	}
	if ($a == 'toggle')
		q("UPDATE decorations SET available=1-available WHERE id=?", [$_POST['id']]);
	if ($a == 'edit')
		q("UPDATE decorations SET price=?,quantity=? WHERE id=?", [$_POST['price'], $_POST['quantity'], $_POST['id']]);
	if ($a == 'delete') {
		try {
			q("DELETE FROM decorations WHERE id=?", [$_POST['id']]);
		} catch (Exception $x) {
			flash('Item has rental records. Mark it unavailable instead.');
		}
	}
	go('decorations.php');
}
ahead('Decorations'); ?>
<form class="f" method="post" enctype="multipart/form-data" style="margin-bottom:2rem"><input type="hidden" name="act"
		value="add"><label>Name<input name="name" required></label><label>Description<input
			name="description"></label><label>Rental price per day<input type="number" step="0.01" name="price"
			required></label><label>Quantity<input type="number" name="quantity" value="1" min="1"></label><label>Image<input
			type="file" name="image" accept="image/*"></label><button>Add decoration</button></form>
<table>
	<tr>
		<th>Name</th>
		<th>Price / Qty</th>
		<th>Status</th>
		<th>Actions</th>
	</tr><?php foreach (q("SELECT * FROM decorations") as $d): ?>
		<tr>
			<td><?= e($d['name']) ?></td>
			<td>
				<form method="post" style="display:flex;gap:.3rem"><input type="hidden" name="id" value="<?= $d['id'] ?>"><input
						type="hidden" name="act" value="edit"><input name="price" value="<?= $d['price'] ?>" style="width:90px"><input
						name="quantity" value="<?= $d['quantity'] ?>" style="width:70px"><button class="sm">Save</button></form>
			</td>
			<td><?= $d['available'] ? badge('available') : badge('declined') ?></td>
			<td>
				<form method="post" class="inline"><input type="hidden" name="id" value="<?= $d['id'] ?>"><button class="sm ghost"
						name="act" value="toggle"><?= $d['available'] ? 'Mark unavailable' : 'Mark available' ?></button> <button
						class="sm ghost" name="act" value="delete" onclick="return confirm('Delete this item?')">Delete</button>
				</form>
			</td>
		</tr><?php endforeach; ?>
</table><?php afoot(); ?>