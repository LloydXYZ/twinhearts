<?php require 'config.php';
need();
if ($_POST) {
	$d = $_POST['event_date'];
	if ($d < date('Y-m-d'))
		flash('Choose a date in the future.');
	elseif (q("SELECT id FROM reservations WHERE event_date=? AND status='approved'", [$d]))
		flash('That date is already booked. Please pick another.');
	else {
		$id = q("INSERT INTO reservations(user_id,package_id,event_type,event_date,venue,notes) VALUES(?,?,?,?,?,?)", [user()['id'], $_POST['package_id'], $_POST['event_type'], $d, $_POST['venue'], $_POST['notes']]);
		notify(user()['id'], "Reservation #$id received for $d. We will confirm it shortly.");
		flash('Reservation submitted. Status: pending.');
		go('account.php');
	}
}
head('Reserve');
$booked = q("SELECT DISTINCT event_date FROM reservations WHERE status='approved' AND event_date>=CURDATE() ORDER BY event_date"); ?>
<h1>Reserve an event</h1>
<form class="f" method="post">
	<label>Package<select name="package_id"><?php foreach (q("SELECT * FROM packages WHERE active=1") as $p): ?>
				<option value="<?= $p['id'] ?>" <?= ($_GET['package'] ?? 0) == $p['id'] ? 'selected' : '' ?>><?= e($p['name']) ?> -
					₱<?= number_format($p['price']) ?></option><?php endforeach; ?>
		</select></label>
	<label>Event type<select name="event_type">
			<option>Wedding</option>
			<option>Debut</option>
			<option>Birthday</option>
			<option>Corporate</option>
			<option>Other</option>
		</select></label>
	<label>Event date<input type="date" name="event_date" min="<?= date('Y-m-d') ?>" required></label><label>Venue<input
			name="venue" required></label><label>Notes<textarea name="notes" rows="3"></textarea></label><button>Submit
		reservation</button>
</form>
<p><b>Unavailable dates:</b> <?= $booked ? e(implode(', ', array_column($booked, 'event_date'))) : 'None yet' ?></p>
<?php foot(); ?>