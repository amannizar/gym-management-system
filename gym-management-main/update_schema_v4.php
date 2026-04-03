<?php
require_once "backend/db.php";

$sql = "ALTER TABLE members 
ADD COLUMN subscription_start DATE NULL,
ADD COLUMN subscription_end DATE NULL,
ADD COLUMN plan_id INT DEFAULT 0, -- 0=None, 1=Silver, 2=Gold, 3=Platinum
ADD COLUMN payment_status ENUM('active', 'expired', 'pending') DEFAULT 'pending'";

if (mysqli_query($conn, $sql)) {
    echo "<h3>Schema Updated Successfully! ✅</h3>";
    echo "<p>Added columns: subscription_start, subscription_end, plan_id, payment_status</p>";
} else {
    echo "<h3>Error Updating Schema (Might already exist)</h3>";
    echo mysqli_error($conn);
}
?>
