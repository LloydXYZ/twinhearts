<?php require '../config.php';
ahead('Dashboard');
$c = fn($s) => q($s)[0]['n'];
$s = ['Customers' => $c("SELECT COUNT(*) n FROM users WHERE role='customer'"), 'Pending reservations' => $c("SELECT COUNT(*) n FROM reservations WHERE status='pending'"), 'Approved reservations' => $c("SELECT COUNT(*) n FROM reservations WHERE status='approved'"), 'Declined reservations' => $c("SELECT COUNT(*) n FROM reservations WHERE status='declined'"), 'Upcoming events' => $c("SELECT COUNT(*) n FROM reservations WHERE status='approved' AND event_date>=CURDATE()"), 'Active rentals' => $c("SELECT COUNT(*) n FROM rentals WHERE status='approved'"), 'Pending payments' => $c("SELECT COUNT(*) n FROM payments WHERE status='pending'"), 'Unavailable items' => $c("SELECT COUNT(*) n FROM decorations WHERE available=0")];
echo '<div class="stats">';
foreach ($s as $k => $v)
	echo "<div class='stat'><b>$v</b><br>$k</div>";
echo '</div>';
afoot(); ?>