<?php
// GestaltOrNot — Authentication

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$input = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
}

switch ($action) {
    case 'signup':
        handleSignup($input);
        break;
    case 'signin':
        handleSignin($input);
        break;
    case 'signout':
        handleSignout();
        break;
    case 'me':
        handleMe();
        break;
    case 'forgot':
        handleForgot($input);
        break;
    case 'reset':
        handleReset($input);
        break;
    case 'reviews':
        handleReviews();
        break;
    case 'delete_review':
        handleDeleteReview($input);
        break;
    case 'usage':
        handleUsage();
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}

function handleSignup($input) {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email address']);
        return;
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters']);
        return;
    }

    $db = getDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        $stmt->execute([$email, $hash]);
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'UNIQUE') !== false) {
            http_response_code(409);
            echo json_encode(['error' => 'An account with that email already exists']);
            return;
        }
        http_response_code(500);
        echo json_encode(['error' => 'Could not create account']);
        return;
    }

    $_SESSION['user_id'] = $db->lastInsertId();
    $_SESSION['email'] = $email;

    echo json_encode(['success' => true, 'email' => $email]);
}

function handleSignin($input) {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password required']);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id, email, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid email or password']);
        return;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];

    echo json_encode(['success' => true, 'email' => $user['email']]);
}

function handleSignout() {
    session_destroy();
    echo json_encode(['success' => true]);
}

function handleMe() {
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['signed_in' => true, 'email' => $_SESSION['email']]);
    } else {
        echo json_encode(['signed_in' => false]);
    }
}

function handleForgot($input) {
    $email = trim($input['email'] ?? '');

    // Always return success to avoid revealing whether email exists
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => true]);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $db->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);

        $resetUrl = "https://gestaltornot.com/reset.html?token=" . $token;
        $subject = "Gestalt Or Not — Reset Your Password";
        $body = "You requested a password reset.\n\nClick this link to set a new password:\n$resetUrl\n\nThis link expires in 1 hour.\n\nIf you didn't request this, just ignore this email.";
        $headers = "From: noreply@gestaltornot.com\r\nContent-Type: text/plain; charset=UTF-8";

        mail($email, $subject, $body, $headers);
    }

    echo json_encode(['success' => true]);
}

function handleReset($input) {
    $token = $input['token'] ?? '';
    $password = $input['password'] ?? '';

    if (!$token || strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Valid token and password (8+ characters) required']);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > datetime('now')");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or expired reset link. Request a new one.']);
        return;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
    $stmt->execute([$hash, $user['id']]);

    echo json_encode(['success' => true]);
}

function handleReviews() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not signed in']);
        return;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id, url, created_at, image_path FROM reviews WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $reviews = $stmt->fetchAll();

    echo json_encode(['reviews' => $reviews]);
}

function handleDeleteReview($input) {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Not signed in']);
        return;
    }

    $id = $input['id'] ?? '';
    if (!$id || !preg_match('/^[a-f0-9]{16}$/', $id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid review ID']);
        return;
    }

    $db = getDB();

    // Verify ownership and get image path
    $stmt = $db->prepare("SELECT image_path FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $review = $stmt->fetch();

    if (!$review) {
        http_response_code(404);
        echo json_encode(['error' => 'Review not found']);
        return;
    }

    // Delete image file if it exists
    if ($review['image_path']) {
        $filePath = __DIR__ . '/' . $review['image_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete DB record
    $stmt = $db->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);

    echo json_encode(['success' => true]);
}

function handleUsage() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['signed_in' => false, 'max' => 5]);
        return;
    }

    $db = getDB();

    // Check if admin
    $stmt = $db->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $isAdmin = ($user && $user['email'] === 'cwodtke@stanford.edu');

    // Count today's reviews
    $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM reviews WHERE user_id = ? AND date(created_at) = date('now')");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();

    echo json_encode([
        'signed_in' => true,
        'count' => (int)$row['cnt'],
        'max' => $isAdmin ? null : 10
    ]);
}
