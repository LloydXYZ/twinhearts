<?php require 'config.php';
head('Home');
$p = q("SELECT * FROM packages WHERE active=1 LIMIT 3"); ?>
<div class="hero">
	<h1>Weddings and celebrations, styled by Twin Hearts</h1>
	<p>Reserve your event date, choose a package, and rent decorations online. We confirm every booking so your date is
		never double-booked.</p><a class="btn" href="reserve.php">Reserve your date</a> <a class="btn ghost"
		href="decorations.php" style="background:#fff;color:var(--rose);border:1px solid var(--rose)">Browse decorations</a>
</div>
<h2>Popular packages</h2>
<div class="grid"><?php foreach ($p as $r): ?>
		<div class="card">
			<h3><?= e($r['name']) ?></h3>
			<p><?= e($r['description']) ?></p>
			<div class="price">₱<?= number_format($r['price']) ?></div>
		</div><?php endforeach; ?>
</div>
<h2 style="margin-top:2.5rem">About us</h2>
<p>Twin Hearts Wedding and Events plans and styles weddings, debuts, and private events. Contact: 09XX-XXX-XXXX &middot;
	hello@twinhearts.com</p>
<?php foot(); ?>