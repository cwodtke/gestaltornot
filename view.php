<?php
// GestaltOrNot — View Shared Review

require_once __DIR__ . '/db.php';

$id = $_GET['id'] ?? null;

if (!$id || !preg_match('/^[a-f0-9]{16}$/', $id)) {
    http_response_code(404);
    die('Review not found');
}

// Try database first
$review = null;
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, feedback, screenshot, url, image_path, created_at as created FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // DB not available, fall through to JSON
}

// Fall back to JSON file for legacy reviews
if (!$review) {
    $filename = __DIR__ . '/reviews/' . $id . '.json';
    if (!file_exists($filename)) {
        http_response_code(404);
        die('Review not found');
    }
    $review = json_decode(file_get_contents($filename), true);
}

if (!$review) {
    http_response_code(404);
    die('Review not found');
}

$feedback = htmlspecialchars($review['feedback']);
$imagePath = $review['image_path'] ?? null;
$screenshot = $review['screenshot'] ?? null;
$created = $review['created'] ?? 'Unknown date';
$analyzedUrl = $review['url'] ?? null;

// Determine image source: prefer file path, fall back to inline base64
$imageSrc = null;
if ($imagePath && file_exists(__DIR__ . '/' . $imagePath)) {
    $imageSrc = $imagePath;
} elseif ($screenshot) {
    $imageSrc = $screenshot;
}

// Build glossary alias map from glossary-data.php
$glossaryAliases = [];
$glossaryTerms = require __DIR__ . '/glossary-data.php';
foreach ($glossaryTerms as $term) {
    foreach ($term['aliases'] as $alias) {
        $glossaryAliases[strtolower($alias)] = $term['slug'];
    }
}

// Convert markdown-ish feedback to simple HTML
function formatFeedbackPHP($text) {
    global $glossaryAliases;
    $text = htmlspecialchars($text);
    // Bold
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
    // Italic — link to glossary if matched
    $text = preg_replace_callback('/\*([^*]+)\*/', function($m) use ($glossaryAliases) {
        $term = $m[1];
        $slug = $glossaryAliases[strtolower($term)] ?? null;
        if ($slug) {
            return '<a href="/glossary.php#' . htmlspecialchars($slug) . '" target="_blank" class="design-term-link"><em>' . $term . '</em></a>';
        }
        return '<em>' . $term . '</em>';
    }, $text);
    // Line breaks
    $text = nl2br($text);
    return $text;
}

$formattedFeedback = formatFeedbackPHP($review['feedback']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Review — Gestalt Or Not</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:title" content="Design Review — Gestalt Or Not">
    <meta property="og:description" content="AI-powered design feedback based on visual design principles.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://gestaltornot.com/view.php?id=<?= htmlspecialchars($id) ?>">
    <?php if ($imagePath): ?>
    <meta property="og:image" content="https://gestaltornot.com/<?= htmlspecialchars($imagePath) ?>">
    <?php elseif ($screenshot && strpos($screenshot, 'data:') !== 0): ?>
    <meta property="og:image" content="<?= htmlspecialchars($screenshot) ?>">
    <?php endif; ?>

    <style>
        .shared-review {
            margin-top: 1rem;
        }
        .review-meta {
            font-size: 0.875rem;
            color: var(--gray-mid);
            margin-bottom: 2rem;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: var(--black);
            text-decoration: none;
            font-weight: 700;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <span class="shape circle"></span>
            <span class="shape square"></span>
            <span class="shape triangle"></span>
        </div>
        <a href="/" style="text-decoration: none; color: inherit;"><h1>Gestalt Or Not</h1></a>
        <p class="tagline">Design feedback powered by visual principles.</p>
    </header>

    <main>
        <section class="shared-review">
            <p class="review-meta">
                Review created <?= htmlspecialchars($created) ?>
                <?php if ($analyzedUrl): ?>
                    · Analyzed: <?= htmlspecialchars($analyzedUrl) ?>
                <?php endif; ?>
            </p>

            <div class="feedback">
                <?php if ($imageSrc): ?>
                <div class="screenshot-preview">
                    <h3>Screenshot Analyzed</h3>
                    <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Screenshot of analyzed design">
                </div>
                <?php endif; ?>

                <div class="feedback-content">
                    <?= $formattedFeedback ?>
                </div>
            </div>

            <a href="/" class="back-link">← Analyze your own design</a>
        </section>
    </main>

    <footer>
        <p>Built to teach design principles. <a href="/glossary.php" class="support-link">Design Glossary</a></p>
        <p class="subtle">Based on Gestalt psychology & visual design heuristics.</p>
    </footer>
</body>
</html>
