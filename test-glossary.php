<?php
// Quick test — delete after verification
$terms = require __DIR__ . '/glossary-data.php';
$aliases = [];
foreach ($terms as $t) {
    foreach ($t['aliases'] as $a) {
        $aliases[strtolower($a)] = $t['slug'];
    }
}

echo "Alias count: " . count($aliases) . "\n";

// Test matching
$tests = ['visual hierarchy', 'proximity', 'contrast', 'information architecture', 'scannability', 'figure-ground', 'affordance', 'feedback'];
foreach ($tests as $t) {
    $slug = $aliases[strtolower($t)] ?? 'NO MATCH';
    echo "  *{$t}* => {$slug}\n";
}

// Test formatFeedbackPHP output
$sample = "Designers call this lack of *visual hierarchy*. The *proximity* of elements matters. But *information architecture* stays plain italic.";

$text = htmlspecialchars($sample);
$text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
$text = preg_replace_callback('/\*([^*]+)\*/', function($m) use ($aliases) {
    $term = $m[1];
    $slug = $aliases[strtolower($term)] ?? null;
    if ($slug) {
        return '<a href="/glossary.php#' . htmlspecialchars($slug) . '" target="_blank" class="design-term-link"><em>' . $term . '</em></a>';
    }
    return '<em>' . $term . '</em>';
}, $text);

echo "\nFormatted output:\n{$text}\n";
