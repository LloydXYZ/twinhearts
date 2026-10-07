<?php require 'config.php';
head('Services'); ?>
<h1>Services and packages</h1>
<div class="grid"><?php foreach (q("SELECT * FROM packages WHERE active=1") as $r): ?>
		<div class="card">
			<h3><?= e($r['name']) ?></h3>
			<p><?= nl2br(e($r['description'])) ?></p>
			<div class="price">₱<?= number_format($r['price'], 2) ?></div>
			<p><a class="btn sm" href="reserve.php?package=<?= $r['id'] ?>">Reserve this package</a></p>
		</div><?php endforeach; ?>
</div><?php foot(); ?>