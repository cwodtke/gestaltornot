<?php
// GestaltOrNot — Design Glossary
$terms = require __DIR__ . '/glossary-data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Glossary — Gestalt Or Not</title>
    <meta name="description" content="Visual design glossary: Gestalt principles, hierarchy, contrast, typography, and more. Each term explained with examples.">

    <meta property="og:title" content="Design Glossary — Gestalt Or Not">
    <meta property="og:description" content="30 visual design terms explained with geometric illustrations. Gestalt principles, hierarchy, affordance, and more.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://gestaltornot.com/glossary.php">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Glossary-specific styles */
        .glossary-intro {
            margin-bottom: 2rem;
            opacity: .75;
            line-height: 1.6;
            max-width: 70ch;
        }

        .glossary-toc {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 3px solid var(--ink);
        }

        .glossary-chip {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border: 1px solid var(--ink);
            text-decoration: none;
            color: var(--ink);
            font-size: 0.8125rem;
            font-weight: 700;
            font-family: var(--font);
            transition: background 0.15s ease, color 0.15s ease;
        }

        .glossary-chip:hover {
            background: var(--ink);
            color: var(--paper);
        }

        .glossary-term {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--hair);
            scroll-margin-top: 1rem;
        }

        .glossary-term:last-child {
            border-bottom: none;
        }

        .glossary-term-name {
            font-family: var(--display);
            font-size: 1.4rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            margin: 0 0 0.75rem;
        }

        .glossary-term-name a {
            color: var(--ink);
            text-decoration: none;
        }

        .glossary-term-name a:hover {
            color: var(--blue);
        }

        .glossary-term-def {
            line-height: 1.7;
            opacity: .8;
            max-width: 65ch;
        }

        .glossary-term-example {
            margin-top: 1rem;
            background: var(--paper);
            border: 1px solid var(--ink);
            padding: 1rem;
            display: inline-block;
        }

        .glossary-term-example svg {
            width: 180px;
            height: auto;
            display: block;
        }

        .glossary-section-label {
            font-family: var(--display);
            font-size: 1.3rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            color: var(--ink);
            margin-bottom: 1.5rem;
            margin-top: 2.5rem;
            padding: 0.4rem 0 0.5rem 1rem;
            border-left: 10px solid var(--red);
            border-bottom: 3px solid var(--ink);
        }

        .references-section {
            margin-top: 3rem;
        }

        .references-section h2 {
            font-family: var(--display);
            font-size: 1.4rem;
            font-weight: 400;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .reference-list {
            margin: 0;
            padding-left: 1.25rem;
            opacity: .85;
            line-height: 1.7;
        }

        .reference-list li {
            margin-bottom: 0.75rem;
        }

        .reference-list a {
            color: var(--blue);
            font-weight: 700;
            text-decoration: underline;
            text-decoration-thickness: 2px;
        }

        .reference-list a:hover {
            color: var(--ink);
        }

        @media (max-width: 600px) {
            .glossary-term-example svg {
                width: 140px;
            }

            .glossary-chip {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="site-bar">
        <span class="mark" aria-hidden="true">
            <span class="m-circle"></span>
            <span class="m-square"></span>
            <span class="m-triangle"></span>
        </span>
        <a href="/" class="wordmark">Gestalt <span class="or">Or</span> Not</a>
        <span class="page-label">Design Glossary</span>
    </div>

    <main class="flow">
        <p class="glossary-intro">
            These are the design principles and concepts referenced in Gestalt Or Not feedback.
            Each term links back here when it appears in a review, so you can learn as you go.
        </p>

        <!-- Table of Contents -->
        <nav class="glossary-toc" aria-label="Term navigation">
            <?php foreach ($terms as $term): ?>
            <a href="#<?= htmlspecialchars($term['slug']) ?>" class="glossary-chip"><?= htmlspecialchars($term['name']) ?></a>
            <?php endforeach; ?>
        </nav>

        <!-- Gestalt Principles -->
        <div class="glossary-section-label">Gestalt Principles</div>
        <?php
        $sections = [
            'gestalt' => ['proximity','similarity','continuity','closure','figure-ground','common-region','common-fate'],
            'visual' => ['visual-hierarchy','contrast','alignment','repetition','whitespace','balance','emphasis','scale','color-theory','typography'],
            'interaction' => ['affordance','feedback','mapping','constraint','progressive-disclosure','fitts-law','hicks-law'],
            'layout' => ['grid-system','responsive-design','visual-weight','rhythm','unity'],
            'readability' => ['readability'],
            'info-design' => ['composition','data-ink-ratio'],
        ];
        $sectionLabels = [
            'gestalt' => 'Gestalt Principles',
            'visual' => 'Visual Design',
            'interaction' => 'Interaction & UX',
            'layout' => 'Layout',
            'readability' => 'Readability',
            'info-design' => 'Composition & Information Design',
        ];
        $termsBySlug = [];
        foreach ($terms as $t) {
            $termsBySlug[$t['slug']] = $t;
        }
        $first = true;
        foreach ($sections as $sectionKey => $slugs):
            if (!$first): ?>
        <div class="glossary-section-label"><?= $sectionLabels[$sectionKey] ?></div>
            <?php endif;
            $first = false;
            foreach ($slugs as $slug):
                $term = $termsBySlug[$slug] ?? null;
                if (!$term) continue;
        ?>
        <article class="glossary-term" id="<?= htmlspecialchars($term['slug']) ?>">
            <h2 class="glossary-term-name">
                <a href="#<?= htmlspecialchars($term['slug']) ?>"><?= htmlspecialchars($term['name']) ?></a>
            </h2>
            <p class="glossary-term-def"><?= htmlspecialchars($term['definition']) ?></p>
            <?php if (!empty($term['example'])): ?>
            <div class="glossary-term-example">
                <?= $term['example'] ?>
            </div>
            <?php endif; ?>
        </article>
        <?php
            endforeach;
        endforeach;
        ?>

        <section class="references-section">
            <h2>References</h2>
            <p class="glossary-intro">These resources informed the heuristics, terminology, and visual design guidance used by Gestalt Or Not.</p>
            <ul class="reference-list">
                <li><strong>Classic usability</strong></li>
                <li><a href="https://lnkd.in/dcqck-vq" target="_blank" rel="noopener">Nielsen’s 10 Usability Heuristics</a></li>
                <li><a href="https://lnkd.in/ex2K3xNc" target="_blank" rel="noopener">Shneiderman’s 8 Golden Rules</a></li>
                <li><a href="https://lnkd.in/eA4nK7Kw" target="_blank" rel="noopener">Gerhardt-Powals’ Cognitive Engineering Principles</a></li>
                <li><a href="https://lnkd.in/eUVj9sBd" target="_blank" rel="noopener">Bastien &amp; Scapin’s Ergonomic Criteria</a></li>
                <li><strong>Behavioural psychology</strong></li>
                <li><a href="https://lnkd.in/ejafVSM3" target="_blank" rel="noopener">Hick’s Law</a></li>
                <li><a href="https://lnkd.in/eGvNwUrS" target="_blank" rel="noopener">Fitts’s Law</a></li>
                <li><a href="https://lnkd.in/eMS4HhSc" target="_blank" rel="noopener">Miller’s Law</a></li>
                <li><a href="https://lnkd.in/egaWvdPA" target="_blank" rel="noopener">Jakob’s Law</a></li>
                <li><a href="https://lnkd.in/eQmgRmwn" target="_blank" rel="noopener">Peak-end rule</a></li>
                <li><strong>Trust and persuasion</strong></li>
                <li><a href="https://behaviormodel.org/" target="_blank" rel="noopener">Fogg Behaviour Model</a></li>
                <li><a href="https://lnkd.in/eM-V_F8T" target="_blank" rel="noopener">Cialdini’s Principles of Influence</a></li>
                <li><strong>Interaction design</strong></li>
                <li><a href="https://lnkd.in/e3E5sKJr" target="_blank" rel="noopener">Gestalt Principles</a></li>
                <li><a href="https://lnkd.in/e3dRVUX9" target="_blank" rel="noopener">Norman’s Design Principles</a></li>
                <li><a href="https://lnkd.in/eV3uyze6" target="_blank" rel="noopener">Tognazzini’s First Principles of Interaction Design</a></li>
                <li><strong>Accessibility</strong></li>
                <li><a href="https://lnkd.in/eJmg7hfd" target="_blank" rel="noopener">WCAG 2.1 Quick Reference</a></li>
                <li><strong>Content design</strong></li>
                <li><a href="https://lnkd.in/eJ6K2c2M" target="_blank" rel="noopener">The 10 Content Design Heuristics</a></li>
            </ul>
        </section>

        <a href="/" class="back-link">&larr; Analyze your own design</a>
    </main>

    <footer class="site-footer">
        <p>Built to teach design principles.</p>
        <p>Based on Gestalt psychology &amp; visual design heuristics. <a href="https://www.paypal.com/donate/?hosted_button_id=QRDU77Z7K56XG" target="_blank" rel="noopener" class="support-link">Support this project</a></p>
    </footer>
</body>
</html>
