<?php
// FILE: /crons/absentee.php
// Cron script to automatically check for active members missing 7 or more consecutive days without attendance.
// Uses sms_absent_7 template setting.
// Add to InfinityFree Cron running at 10:00 AM every Monday.

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Find members who DO NOT exist in attendance for the last 7 days and who are Active.
$query = "SELECT m.name, m.phone FROM members m 
    WHERE m.status='Active' AND m.phone != '' AND NOT EXISTS (
        SELECT 1 FROM attendance a WHERE a.member_id = m.id AND a.present = 1 AND a.service_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    )";

$res = $conn->query($query);

$absent_msg = getSetting($conn, 'sms_absent_7');
if (empty($absent_msg)) $absent_msg = "Hello {name}, we missed you in church recently. We hope all is well! Let us know if you need prayers. - Dominion City";

$count = 0;
while($row = $res->fetch_assoc()) {
    $personalized_msg = str_replace('{name}', $row['name'], $absent_msg);
    sendSMS($row['phone'], $personalized_msg);
    $count++;
}

echo "Absentee Cron complete. Sent follow-up SMS to $count members.";
?>
