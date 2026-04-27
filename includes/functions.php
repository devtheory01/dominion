<?php
// FILE: /includes/functions.php
// Houses reusable helper functions across the project.
// Features data sanitization, file uploads, settings retrieval, SMS (Termii), and Emailing.
// Prevents code repetition and ensures consistency.

require_once 'db.php';

function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

function uploadImage($file, $folder) {
    if ($file['error'] == 0) {
        $filename = time() . '_' . preg_replace("/[^A-Za-z0-9\-\.]/", "", basename($file['name']));
        $target_path = __DIR__ . "/../$folder/" . $filename;
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return "$folder/$filename";
        }
    }
    return '';
}

function getSetting($conn, $key) {
    $res = $conn->query("SELECT $key FROM settings LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row[$key] ?? '';
    }
    return '';
}

function sendSMS($phone, $msg) {
    global $conn;
    $api_key = getSetting($conn, 'termii_key');
    $sender = getSetting($conn, 'termii_sender');
    
    if (empty($api_key)) return false;

    // Convert phone to international format if necessary, assuming Nigerian
    if(substr($phone, 0, 1) == '0') $phone = '234' . substr($phone, 1);
    $phone = preg_replace('/[^0-9]/', '', $phone);

    $data = [
        "to" => $phone,
        "from" => $sender,
        "sms" => $msg,
        "type" => "plain",
        "api_key" => $api_key,
        "channel" => "generic"
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.ng.termii.com/api/sms/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    ]);
    
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function sendEmail($to, $subject, $message) {
    // Basic PHP mail fallback. Note InfinityFree might block standard mail().
    global $conn;
    $site_name = getSetting($conn, 'site_name');
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: $site_name <noreply@{$_SERVER['HTTP_HOST']}>" . "\r\n";
    return mail($to, $subject, $message, $headers);
}
?>
