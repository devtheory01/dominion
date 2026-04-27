<?php
// FILE: /crons/birthday.php
// Cron script to automatically check for active members whose DOB matches today.
// Triggers the sendSMS and sendEmail routines using templates from settings.
// Needs to be added to InfinityFree Cron tab running daily at 08:00 AM.

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$today = date('m-d');
$query = "SELECT name, phone, email FROM members WHERE DATE_FORMAT(dob,'%m-%d') = '$today' AND status='Active'";
$res = $conn->query($query);

$bday_msg = getSetting($conn, 'sms_birthday');
if (empty($bday_msg)) $bday_msg = "Happy Birthday {name}! God bless your new age. - Dominion City";

$count = 0;
while($row = $res->fetch_assoc()) {
    $personalized_msg = str_replace('{name}', $row['name'], $bday_msg);
    
    // Send SMS
    if(!empty($row['phone'])) sendSMS($row['phone'], $personalized_msg);
    
    // Send Email
    if(!empty($row['email'])) sendEmail($row['email'], "Happy Birthday!", "<p>$personalized_msg</p>");
    
    $count++;
}

echo "Birthday Cron complete. Sent to $count members on $today.";
?>
