<?php
// GestaltOrNot — Save Review for Sharing

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['feedback']) || empty($input['feedback'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No feedback provided']);
    exit;
}

// Generate unique ID
$id = bin2hex(random_bytes(8));

// Save image to disk if provided
$imagePath = null;
if (!empty($input['image'])) {
    $uploadsDir = __DIR__ . '/uploads';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    // Strip data URL prefix if present (e.g. "data:image/png;base64,")
    $base64 = $input['image'];
    if (strpos($base64, ',') !== false) {
        $base64 = substr($base64, strpos($base64, ',') + 1);
    }

    $decoded = base64_decode($base64, true);
    if ($decoded !== false) {
        $filePath = $uploadsDir . '/' . $id . '.png';
        file_put_contents($filePath, $decoded);
        $imagePath = 'uploads/' . $id . '.png';
    }
}

// Save to database
$db = getDB();
$stmt = $db->prepare("INSERT INTO reviews (id, user_id, feedback, screenshot, url, image_path) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $id,
    $_SESSION['user_id'] ?? null,
    $input['feedback'],
    $input['screenshot'] ?? null,
    $input['url'] ?? null,
    $imagePath
]);

echo json_encode([
    'success' => true,
    'id' => $id,
    'share_url' => 'https://gestaltornot.com/view.php?id=' . $id
]);
