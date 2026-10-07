<?php require 'config.php';
head('Decorations'); ?>
<h1>Decoration rentals</h1>
<div class="grid"><?php foreach (q("SELECT * FROM decorations") as $r): ?>
		<div class="card">
			<?php if ($r['image']): ?><img src="<?= BASE ?>uploads/<?= e($r['image']) ?>" alt=""
					style="width:100%;height:160px;object-fit:cover"><?php endif; ?>
			<h3><?= e($r['name']) ?></h3>
			<p><?= e($r['description']) ?></p>
			<div class="price">₱<?= number_format($r['price'], 2) ?> per day</div>
			<p>Stock: <?= $r['quantity'] ?> 	<?= $r['available'] ? badge('available') : badge('declined') ?></p>
			<?php if ($r['available']): ?><a class="btn sm" href="rent.php?d=<?= $r['id'] ?>">Rent</a><?php endif; ?>
		</div><?php endforeach; ?>
</div><?php foot(); ?>