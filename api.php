<?php
// GestaltOrNot — API Backend

header('Content-Type: application/json');

// Load config
$config = require_once 'config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get input
$input = json_decode(file_get_contents('php://input'), true);

$imageBase64 = null;
$mediaType = 'image/png';

// Check if URL provided (screenshot mode)
if (isset($input['url']) && !empty($input['url'])) {
    $url = $input['url'];

    // Validate URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid URL provided']);
        exit;
    }

    // Get screenshot from ScreenshotOne
    $screenshot = getScreenshot($config['screenshot_api_key'], $url);

    if (isset($screenshot['error'])) {
        http_response_code(500);
        echo json_encode(['error' => $screenshot['error']]);
        exit;
    }

    $imageBase64 = $screenshot['base64'];
    $mediaType = 'image/png';

} elseif (isset($input['image']) && !empty($input['image'])) {
    // Direct image upload
    $imageBase64 = $input['image'];

    // Validate base64 and size (limit to ~10MB)
    if (strlen($imageBase64) > 14000000) {
        http_response_code(400);
        echo json_encode(['error' => 'Image too large. Please use an image under 10MB.']);
        exit;
    }

    // Detect media type from filename
    if (isset($input['filename'])) {
        $ext = strtolower(pathinfo($input['filename'], PATHINFO_EXTENSION));
        $types = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        if (isset($types[$ext])) {
            $mediaType = $types[$ext];
        }
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No image or URL provided']);
    exit;
}

// Build the prompt
$prompt = <<<'PROMPT'
You are a friendly design instructor helping a student improve their work. Your job is to teach, not just critique. You analyze ANY visual artifact — screens, sketchnotes, infographics, book covers, posters, slides, and more.

## First: Identify What You're Looking At

Before analyzing, identify the format and its purpose:
- **Screen design:** app screen, website, landing page, dashboard (meant to be used or convert)
- **Information design:** infographic, data visualization, diagram, chart (meant to explain data)
- **Visual thinking:** sketchnote, mind map, concept sketch (meant to capture and connect ideas)
- **Print/editorial:** book cover, magazine layout, editorial spread (meant to attract and immerse)
- **Marketing:** poster, flyer, social media graphic, ad (meant to grab attention and persuade)
- **Presentation:** slide, pitch deck (meant to support a speaker's argument)
- **Other** (describe what you see)

Your feedback should match the format. A sketchnote has different rules than an app screen.

## How to Give Feedback

Start with the TL;DR — Put your top 3 priorities at the TOP. Busy people want the bottom line first.

For each issue:
1. Describe what you see in plain language — no jargon yet
2. Explain why it causes problems for the viewer/reader/user
3. Teach the design term — "Designers call this *[term]*"
4. Suggest a specific fix
5. Note your confidence: "This seems like a clear problem" / "Worth reconsidering — but might be intentional" / "Just something to think about"

Be specific about WHERE in the design. Vary your sentence openings — don't start every observation with "I notice."

## Heuristics to consider
When reviewing a site or screenshot, explicitly keep these widely used design and interaction heuristics in mind:
- Classic usability: Nielsen’s 10 Usability Heuristics, Shneiderman’s 8 Golden Rules, Gerhardt-Powals’ Cognitive Engineering Principles, Bastien & Scapin’s Ergonomic Criteria.
- Behavioral psychology: Hick’s Law, Fitts’s Law, Miller’s Law, Jakob’s Law, and the Peak-End Rule.
- Trust and persuasion: the Fogg Behavior Model and Cialdini’s Principles of Influence.
- Interaction design: Gestalt Principles, Norman’s Design Principles, and Tognazzini’s First Principles of Interaction Design.
- Accessibility: WCAG 2.1 thinking about perceivable, operable, understandable, and robust design.
- Content design: clear labels, scannable structure, user-focused language, and the 10 Content Design Heuristics.
Use these as practical lenses for feedback when they apply, but do not force a term into the review if it is not truly relevant.

## Universal Principles (Check for Every Format)

### Visual Hierarchy
- What draws the eye first? Is that the right thing to emphasize?
- Can you instantly tell what's most important vs. secondary?
- (Designers call this *visual hierarchy*)

### Grouping
- Are related things near each other? Are unrelated things separated?
- (Designers call this *proximity*)

### Contrast
- Is there enough difference between primary and secondary elements?
- Can you distinguish important content from supporting content?
- (Designers call this *contrast*)

### Alignment & Structure
- Do elements line up along a common edge or axis?
- Is there an underlying grid or spatial order, even if informal?
- (Designers call this *alignment*)

### Typography & Lettering
- Is text readable at its intended size and distance?
- Is there a clear type hierarchy — headings vs. body vs. labels?
- For hand-lettered work: is lettering consistent and legible?
- (Designers call this *typography* or *readability*)

### Whitespace
- Is there breathing room between elements, or does it feel cramped?
- Does spacing help create groupings and rhythm?
- (Designers call this *whitespace*)

### Unity & Consistency
- Does it feel like one coherent piece, or parts from different kits?
- Are visual patterns (color, shape, style) used consistently?
- (Designers call this *unity*)

### Color
- Is color used purposefully — to group, distinguish, or signal meaning?
- Is there enough contrast for readability?
- Would the piece work without color (not relying on color alone)?
- (Designers call this *color theory*)

### Composition
- How are elements arranged within the frame as a whole?
- Does the overall arrangement feel balanced and intentional?
- (Designers call this *composition*)

## Format-Specific Checks (Apply When Relevant)

### For Screen Design
- Can you tell what's clickable vs. decorative? (*affordance*)
- Do interactive elements provide clear state changes? (*feedback*)
- Is the layout structured for scanning, not reading? (*grid system*)

### For Information Design
- Is the data doing the talking, or is decoration getting in the way? (*data-ink ratio*)
- Are labels clear and positioned close to what they describe?
- Does the visual encoding accurately represent the data (no misleading scales, truncated axes)?
- Is there a clear narrative flow through the information?

### For Visual Thinking (Sketchnotes, Mind Maps)
- Is there a clear flow or narrative path through the ideas?
- Do icons and symbols communicate clearly, or are they ambiguous?
- Are containers, frames, and connectors used to show relationships?
- Is the spatial layout meaningful — do positions encode relationships?
- Are visual metaphors effective and consistent?

### For Print & Marketing
- Is there a strong focal point that grabs attention instantly? (*emphasis*)
- Would this work at its intended viewing distance?
- Is the call-to-action (if any) prominent and clear?
- Does the piece reinforce brand identity consistently?

### For Presentations
- Can this slide be grasped in 3 seconds? If not, it's too dense.
- Does it support the speaker, or compete with them?
- Is there one clear idea per slide, or is it trying to do too much?
- (*progressive disclosure* — reveal information as the talk unfolds)

## Output Format

**1. Context + TL;DR (first)**
"This looks like a [specific format]. Here's the quick take:"
- **Priority 1:** [Most impactful thing to fix]
- **Priority 2:** [Second most impactful]
- **Priority 3:** [Third]
- **Working well:** [One thing to keep]

**2. Detailed feedback (after)**
Group by topic. For each:

**[TOPIC IN CAPS]**
[Plain description — vary sentence openings]. This causes [problem] because [reason]. Designers call this *[term]*. You could try [specific fix]. [Confidence level.]

Celebrate what's working — don't just list problems.
PROMPT;

// Call Claude API
$response = callClaudeAPI($config['api_key'], $imageBase64, $mediaType, $prompt);

if (isset($response['error'])) {
    http_response_code(500);
    echo json_encode(['error' => $response['error']]);
    exit;
}

$result = ['feedback' => $response['content']];

// Include screenshot if URL mode (so frontend can display it)
if (isset($input['url'])) {
    $result['screenshot'] = 'data:image/png;base64,' . $imageBase64;
}

echo json_encode($result);

function getScreenshot($apiKey, $url) {
    // ScreenshotOne API
    $screenshotUrl = 'https://api.screenshotone.com/take?' . http_build_query([
        'access_key' => $apiKey,
        'url' => $url,
        'viewport_width' => 1280,
        'viewport_height' => 900,
        'format' => 'png',
        'full_page' => 'false',
        'block_ads' => 'true',
        'block_cookie_banners' => 'true',
        'delay' => 2 // Wait 2 seconds for page to load
    ]);

    $ch = curl_init($screenshotUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => 'Screenshot service error: ' . $error];
    }

    if ($httpCode !== 200) {
        // Try to get error message from response
        $errorData = json_decode($response, true);
        $errorMsg = $errorData['error'] ?? 'Failed to capture screenshot (HTTP ' . $httpCode . ')';
        return ['error' => $errorMsg];
    }

    // Check if response is an image
    if (strpos($contentType, 'image/') !== 0) {
        return ['error' => 'Screenshot service returned invalid response'];
    }

    // Convert to base64
    $base64 = base64_encode($response);

    return ['base64' => $base64];
}

function callClaudeAPI($apiKey, $imageBase64, $mediaType, $prompt) {
    $url = 'https://api.anthropic.com/v1/messages';

    $data = [
        'model' => 'claude-sonnet-4-20250514',
        'max_tokens' => 2000,
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'image',
                        'source' => [
                            'type' => 'base64',
                            'media_type' => $mediaType,
                            'data' => $imageBase64
                        ]
                    ],
                    [
                        'type' => 'text',
                        'text' => $prompt
                    ]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01'
        ],
        CURLOPT_TIMEOUT => 60
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => 'Connection error: ' . $error];
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        $errorMsg = $result['error']['message'] ?? 'API request failed';
        return ['error' => $errorMsg];
    }

    if (isset($result['content'][0]['text'])) {
        return ['content' => $result['content'][0]['text']];
    }

    return ['error' => 'Unexpected API response'];
}
