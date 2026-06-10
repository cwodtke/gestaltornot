<?php
// GestaltOrNot — Glossary Term Data
// Each term: slug, name, definition, example (inline SVG), aliases (lowercase variants Claude might use)

return [

    // --- Gestalt Principles ---

    [
        'slug' => 'proximity',
        'name' => 'Proximity',
        'definition' => 'Elements placed near each other are perceived as a group. The closer items are, the stronger the grouping. This is one of the most powerful and commonly violated Gestalt principles — unrelated items sitting close together mislead the eye.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="25" r="5" fill="#000"/><circle cx="33" cy="25" r="5" fill="#000"/><circle cx="20" cy="38" r="5" fill="#000"/><circle cx="33" cy="38" r="5" fill="#000"/><circle cx="75" cy="25" r="5" fill="#000"/><circle cx="88" cy="25" r="5" fill="#000"/><circle cx="101" cy="25" r="5" fill="#000"/><circle cx="75" cy="38" r="5" fill="#000"/><circle cx="88" cy="38" r="5" fill="#000"/><circle cx="101" cy="38" r="5" fill="#000"/><line x1="53" y1="15" x2="53" y2="50" stroke="#BE1E2D" stroke-width="1" stroke-dasharray="3,3"/><text x="60" y="70" font-size="8" fill="#888" font-family="sans-serif">gap creates groups</text></svg>',
        'aliases' => ['proximity'],
    ],
    [
        'slug' => 'similarity',
        'name' => 'Similarity',
        'definition' => 'Elements that share visual characteristics — shape, color, size, or texture — are perceived as related. Similarity works alongside proximity: grouped items that also look alike form an even stronger association.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="30" r="6" fill="#000"/><circle cx="38" cy="30" r="6" fill="#000"/><circle cx="56" cy="30" r="6" fill="#000"/><rect x="74" y="24" width="12" height="12" fill="#BE1E2D"/><rect x="92" y="24" width="12" height="12" fill="#BE1E2D"/><text x="60" y="65" font-size="8" fill="#888" font-family="sans-serif" text-anchor="middle">same shape = same group</text></svg>',
        'aliases' => ['similarity'],
    ],
    [
        'slug' => 'continuity',
        'name' => 'Continuity',
        'definition' => 'The eye follows smooth paths, lines, and curves in preference to abrupt changes in direction. Elements arranged along a line or curve are perceived as more related than elements not on the path. This is why navigation bars and timelines work.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><path d="M10,60 Q35,10 60,40 Q85,70 110,20" stroke="#000" stroke-width="2" fill="none"/><circle cx="10" cy="60" r="4" fill="#BE1E2D"/><circle cx="35" cy="25" r="4" fill="#BE1E2D"/><circle cx="60" cy="40" r="4" fill="#BE1E2D"/><circle cx="85" cy="55" r="4" fill="#BE1E2D"/><circle cx="110" cy="20" r="4" fill="#BE1E2D"/></svg>',
        'aliases' => ['continuity'],
    ],
    [
        'slug' => 'closure',
        'name' => 'Closure',
        'definition' => 'The mind fills in missing visual information to perceive a complete shape. You don\'t need to draw every line — the brain will close the gaps. This is why icons can be simplified, and why dashed borders still read as contained regions.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><path d="M40,10 A30,30 0 1,1 40,70" stroke="#000" stroke-width="3" fill="none" stroke-dasharray="8,6"/><text x="85" y="45" font-size="8" fill="#888" font-family="sans-serif">brain</text><text x="85" y="55" font-size="8" fill="#888" font-family="sans-serif">closes it</text></svg>',
        'aliases' => ['closure'],
    ],
    [
        'slug' => 'figure-ground',
        'name' => 'Figure-Ground',
        'definition' => 'The eye naturally separates shapes (figures) from their background (ground). When this relationship is ambiguous, users struggle to parse the interface. Good design makes it obvious what\'s content and what\'s background through contrast, elevation, or framing.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect width="120" height="80" fill="#000"/><rect x="25" y="10" width="70" height="60" fill="#fff"/><text x="60" y="45" font-size="9" fill="#000" font-family="sans-serif" text-anchor="middle" font-weight="bold">FIGURE</text><text x="10" y="75" font-size="7" fill="#888" font-family="sans-serif">ground</text></svg>',
        'aliases' => ['figure-ground', 'figure ground'],
    ],
    [
        'slug' => 'common-region',
        'name' => 'Common Region',
        'definition' => 'Elements enclosed within the same boundary are perceived as a group, even if they otherwise differ. Cards, panels, and bordered sections all leverage common region to visually organize content.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="10" width="50" height="60" fill="none" stroke="#000" stroke-width="2"/><circle cx="18" cy="30" r="5" fill="#BE1E2D"/><circle cx="38" cy="30" r="5" fill="#000"/><circle cx="18" cy="50" r="5" fill="#BE1E2D"/><circle cx="38" cy="50" r="5" fill="#000"/><circle cx="78" cy="40" r="5" fill="#888"/><circle cx="98" cy="40" r="5" fill="#888"/></svg>',
        'aliases' => ['common region', 'common-region'],
    ],
    [
        'slug' => 'common-fate',
        'name' => 'Common Fate',
        'definition' => 'Elements that move or change together are perceived as related. In interfaces, this applies to animations, scrolling behavior, and synchronized state changes. If two elements respond to the same action, users treat them as a unit.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><polygon points="15,35 25,30 25,40" fill="#000"/><line x1="25" y1="35" x2="50" y2="35" stroke="#000" stroke-width="2"/><polygon points="15,50 25,45 25,55" fill="#000"/><line x1="25" y1="50" x2="50" y2="50" stroke="#000" stroke-width="2"/><polygon points="80,25 90,35 80,45" fill="#BE1E2D"/><line x1="65" y1="35" x2="80" y2="35" stroke="#BE1E2D" stroke-width="2"/><text x="60" y="70" font-size="8" fill="#888" font-family="sans-serif" text-anchor="middle">same direction = related</text></svg>',
        'aliases' => ['common fate', 'common-fate'],
    ],

    // --- Visual Design ---

    [
        'slug' => 'visual-hierarchy',
        'name' => 'Visual Hierarchy',
        'definition' => 'The arrangement of elements to guide the eye through content in order of importance. Size, color, contrast, spacing, and position all contribute. Without clear hierarchy, users scan randomly and miss what matters.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="8" width="60" height="10" fill="#000"/><rect x="10" y="24" width="100" height="5" fill="#888"/><rect x="10" y="33" width="95" height="5" fill="#888"/><rect x="10" y="42" width="100" height="5" fill="#888"/><rect x="10" y="55" width="40" height="7" fill="#000"/><rect x="10" y="67" width="90" height="4" fill="#ccc"/><rect x="10" y="74" width="85" height="4" fill="#ccc"/></svg>',
        'aliases' => ['visual hierarchy', 'visual-hierarchy', 'hierarchy'],
    ],
    [
        'slug' => 'contrast',
        'name' => 'Contrast',
        'definition' => 'The degree of difference between elements. High contrast draws attention and creates clarity. Low contrast suggests secondary importance. Contrast operates across multiple dimensions: color, size, weight, shape, and spacing.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="15" width="40" height="50" fill="#000"/><rect x="70" y="15" width="40" height="50" fill="#ccc"/><text x="30" y="44" font-size="11" fill="#fff" font-family="sans-serif" text-anchor="middle" font-weight="bold">A</text><text x="90" y="44" font-size="11" fill="#fff" font-family="sans-serif" text-anchor="middle" font-weight="bold">A</text></svg>',
        'aliases' => ['contrast'],
    ],
    [
        'slug' => 'alignment',
        'name' => 'Alignment',
        'definition' => 'Placing elements so their edges or centers line up along a common axis. Alignment creates order, reduces cognitive load, and makes relationships between elements visible. Even small misalignments feel "off" to users.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><line x1="20" y1="5" x2="20" y2="75" stroke="#BE1E2D" stroke-width="1" stroke-dasharray="3,3"/><rect x="20" y="10" width="50" height="8" fill="#000"/><rect x="20" y="25" width="80" height="6" fill="#888"/><rect x="20" y="37" width="70" height="6" fill="#888"/><rect x="20" y="49" width="75" height="6" fill="#888"/><rect x="20" y="63" width="40" height="8" fill="#000"/></svg>',
        'aliases' => ['alignment'],
    ],
    [
        'slug' => 'repetition',
        'name' => 'Repetition',
        'definition' => 'Reusing visual elements — colors, shapes, fonts, spacing patterns — to create consistency and strengthen unity across a design. Repetition makes interfaces learnable: once you understand one card, you understand them all.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="15" width="30" height="50" fill="none" stroke="#000" stroke-width="2"/><rect x="5" y="15" width="30" height="15" fill="#000"/><rect x="45" y="15" width="30" height="50" fill="none" stroke="#000" stroke-width="2"/><rect x="45" y="15" width="30" height="15" fill="#000"/><rect x="85" y="15" width="30" height="50" fill="none" stroke="#000" stroke-width="2"/><rect x="85" y="15" width="30" height="15" fill="#000"/></svg>',
        'aliases' => ['repetition', 'consistency'],
    ],
    [
        'slug' => 'whitespace',
        'name' => 'Whitespace',
        'definition' => 'The empty space between and around elements. Whitespace isn\'t wasted space — it provides breathing room, improves readability, creates groupings through proximity, and signals importance. Crowded layouts overwhelm; generous spacing communicates confidence.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="5" width="50" height="70" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><rect x="7" y="7" width="46" height="8" fill="#000"/><rect x="7" y="17" width="46" height="5" fill="#888"/><rect x="7" y="24" width="46" height="5" fill="#888"/><rect x="7" y="31" width="46" height="5" fill="#888"/><rect x="7" y="38" width="46" height="8" fill="#000"/><rect x="7" y="48" width="46" height="5" fill="#888"/><rect x="7" y="55" width="46" height="5" fill="#888"/><rect x="65" y="5" width="50" height="70" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><rect x="70" y="12" width="40" height="8" fill="#000"/><rect x="70" y="28" width="40" height="4" fill="#888"/><rect x="70" y="35" width="40" height="4" fill="#888"/><rect x="70" y="48" width="40" height="8" fill="#000"/><rect x="70" y="60" width="40" height="4" fill="#888"/></svg>',
        'aliases' => ['whitespace', 'white space', 'negative space'],
    ],
    [
        'slug' => 'balance',
        'name' => 'Balance',
        'definition' => 'The distribution of visual weight across a composition. Symmetrical balance feels stable and formal. Asymmetrical balance feels dynamic but requires careful counterweighting. An unbalanced layout feels like it might topple.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><line x1="60" y1="10" x2="60" y2="55" stroke="#ccc" stroke-width="1" stroke-dasharray="3,3"/><rect x="15" y="20" width="35" height="35" fill="#000"/><rect x="70" y="20" width="35" height="35" fill="#000"/><polygon points="60,60 20,70 100,70" fill="none" stroke="#000" stroke-width="2"/></svg>',
        'aliases' => ['balance', 'symmetry'],
    ],
    [
        'slug' => 'emphasis',
        'name' => 'Emphasis',
        'definition' => 'Making one element stand out from the rest to draw attention. Emphasis is created through contrast in size, color, weight, or position. Every screen should have a clear focal point — if everything is emphasized, nothing is.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="40" r="8" fill="#888"/><circle cx="42" cy="40" r="8" fill="#888"/><circle cx="64" cy="40" r="12" fill="#BE1E2D"/><circle cx="88" cy="40" r="8" fill="#888"/><circle cx="108" cy="40" r="8" fill="#888"/></svg>',
        'aliases' => ['emphasis', 'focal point'],
    ],
    [
        'slug' => 'scale',
        'name' => 'Scale',
        'definition' => 'The relative size of elements compared to each other and their context. Scale establishes hierarchy — larger elements claim more importance. Dramatic scale differences create visual interest; subtle ones confuse rather than communicate.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="35" width="15" height="15" fill="#000"/><rect x="35" y="25" width="25" height="25" fill="#000"/><rect x="70" y="10" width="40" height="40" fill="#BE1E2D"/></svg>',
        'aliases' => ['scale'],
    ],
    [
        'slug' => 'color-theory',
        'name' => 'Color Theory',
        'definition' => 'The principles governing effective use of color in design. This includes color harmony (complementary, analogous, triadic), color psychology (red = urgency, blue = trust), and functional color (red for errors, green for success). Consistent color usage builds meaning over time.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="35" cy="35" r="18" fill="#BE1E2D" opacity="0.8"/><circle cx="55" cy="35" r="18" fill="#1a5276" opacity="0.8"/><circle cx="45" cy="52" r="18" fill="#F9A825" opacity="0.8"/></svg>',
        'aliases' => ['color theory', 'color-theory', 'colour theory', 'color'],
    ],
    [
        'slug' => 'typography',
        'name' => 'Typography',
        'definition' => 'The art of arranging type for readability, hierarchy, and visual appeal. Good typography uses a limited type scale, maintains consistent line heights, and ensures adequate contrast between text and background. Type choices convey personality — a serif font says something different than a geometric sans.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><text x="10" y="25" font-size="16" fill="#000" font-family="serif" font-weight="bold">Aa</text><text x="50" y="25" font-size="16" fill="#000" font-family="sans-serif" font-weight="bold">Aa</text><text x="90" y="25" font-size="16" fill="#000" font-family="monospace">Aa</text><rect x="10" y="40" width="100" height="3" fill="#000"/><rect x="10" y="48" width="100" height="2" fill="#888"/><rect x="10" y="55" width="100" height="2" fill="#888"/><rect x="10" y="62" width="80" height="2" fill="#888"/></svg>',
        'aliases' => ['typography', 'type', 'typographic'],
    ],

    // --- Interaction / UX ---

    [
        'slug' => 'affordance',
        'name' => 'Affordance',
        'definition' => 'Visual cues that suggest how an element can be used. A raised button affords pressing. An underlined word affords clicking. When affordances are missing, users don\'t know what\'s interactive. When they\'re false (a non-clickable thing that looks clickable), users get frustrated.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="15" width="45" height="24" rx="0" fill="#000"/><text x="32" y="31" font-size="9" fill="#fff" font-family="sans-serif" text-anchor="middle" font-weight="bold">Click</text><rect x="65" y="15" width="45" height="24" fill="none" stroke="#ccc" stroke-width="1"/><text x="87" y="31" font-size="9" fill="#ccc" font-family="sans-serif" text-anchor="middle">Click?</text><text x="32" y="58" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">clear</text><text x="87" y="58" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">unclear</text></svg>',
        'aliases' => ['affordance', 'affordances'],
    ],
    [
        'slug' => 'feedback',
        'name' => 'Feedback',
        'definition' => 'A response from the system that confirms a user\'s action was received and shows its result. Button press states, loading spinners, success messages, and error alerts are all forms of feedback. Without it, users click again, wonder if anything happened, and lose trust.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="20" width="40" height="20" fill="#000"/><text x="30" y="34" font-size="8" fill="#fff" font-family="sans-serif" text-anchor="middle">Submit</text><text x="55" y="34" font-size="12" fill="#000" font-family="sans-serif">→</text><rect x="65" y="18" width="45" height="24" fill="#2E7D32"/><text x="87" y="33" font-size="8" fill="#fff" font-family="sans-serif" text-anchor="middle">Saved ✓</text><text x="60" y="65" font-size="8" fill="#888" font-family="sans-serif" text-anchor="middle">action → confirmation</text></svg>',
        'aliases' => ['feedback'],
    ],
    [
        'slug' => 'mapping',
        'name' => 'Mapping',
        'definition' => 'The relationship between a control and its effect. Good mapping is intuitive — a slider moves left to reduce, right to increase. Bad mapping requires memorization or documentation. The closer the control is to the thing it affects, the clearer the mapping.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="30" width="30" height="20" fill="none" stroke="#000" stroke-width="2"/><text x="25" y="44" font-size="8" fill="#000" font-family="sans-serif" text-anchor="middle">ctrl</text><line x1="45" y1="40" x2="70" y2="40" stroke="#BE1E2D" stroke-width="2" marker-end="url(#arrow)"/><rect x="75" y="30" width="30" height="20" fill="#000"/><text x="90" y="44" font-size="8" fill="#fff" font-family="sans-serif" text-anchor="middle">effect</text><defs><marker id="arrow" markerWidth="8" markerHeight="6" refX="8" refY="3" orient="auto"><path d="M0,0 L8,3 L0,6" fill="#BE1E2D"/></marker></defs></svg>',
        'aliases' => ['mapping'],
    ],
    [
        'slug' => 'constraint',
        'name' => 'Constraint',
        'definition' => 'Limiting the range of possible actions to prevent errors. Greyed-out buttons, disabled fields, date pickers that prevent invalid dates, and character limits are all constraints. Good constraints are invisible — they guide users toward valid choices without feeling restrictive.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="20" width="40" height="20" fill="#000"/><text x="30" y="34" font-size="8" fill="#fff" font-family="sans-serif" text-anchor="middle">Active</text><rect x="65" y="20" width="40" height="20" fill="#ccc"/><text x="85" y="34" font-size="8" fill="#fff" font-family="sans-serif" text-anchor="middle">Disabled</text><line x1="72" y1="45" x2="100" y2="45" stroke="#BE1E2D" stroke-width="1.5"/><text x="85" y="60" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">can\'t click</text></svg>',
        'aliases' => ['constraint', 'constraints'],
    ],
    [
        'slug' => 'progressive-disclosure',
        'name' => 'Progressive Disclosure',
        'definition' => 'Revealing information and options gradually, showing only what\'s needed at each step. This reduces cognitive overload by keeping the interface simple for common tasks while still making advanced features accessible. Accordion menus, "Show more" links, and multi-step wizards all use progressive disclosure.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="10" width="100" height="15" fill="none" stroke="#000" stroke-width="2"/><text x="15" y="21" font-size="8" fill="#000" font-family="sans-serif">Basic options</text><text x="100" y="21" font-size="8" fill="#000" font-family="sans-serif">▼</text><rect x="10" y="30" width="100" height="10" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><rect x="10" y="42" width="100" height="10" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><rect x="10" y="54" width="100" height="10" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><text x="60" y="75" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">expand to reveal more</text></svg>',
        'aliases' => ['progressive disclosure', 'progressive-disclosure'],
    ],
    [
        'slug' => 'fitts-law',
        'name' => 'Fitts\'s Law',
        'definition' => 'The time to reach a target is a function of its size and distance. Larger, closer targets are faster to click. This is why primary buttons should be big, why nav items benefit from generous padding, and why placing destructive actions far from common targets reduces accidental clicks.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="25" cy="40" r="5" fill="#ccc" stroke="#000" stroke-width="1"/><text x="25" y="60" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">hard</text><circle cx="80" cy="40" r="20" fill="#000"/><text x="80" y="44" font-size="9" fill="#fff" font-family="sans-serif" text-anchor="middle">easy</text></svg>',
        'aliases' => ['fitts\'s law', 'fitts\' law', 'fitts law', 'fitt\'s law', 'fitts-law'],
    ],
    [
        'slug' => 'hicks-law',
        'name' => 'Hick\'s Law',
        'definition' => 'Decision time increases logarithmically with the number of choices. More options mean slower decisions. This is why mega-menus overwhelm, why curated defaults outperform long lists, and why breaking a 20-option form into steps reduces abandonment.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="8" y="18" width="30" height="12" fill="#000"/><rect x="8" y="34" width="30" height="12" fill="#000"/><text x="50" y="36" font-size="10" fill="#888" font-family="sans-serif">vs</text><rect x="62" y="10" width="22" height="9" fill="#ccc"/><rect x="86" y="10" width="22" height="9" fill="#ccc"/><rect x="62" y="22" width="22" height="9" fill="#ccc"/><rect x="86" y="22" width="22" height="9" fill="#ccc"/><rect x="62" y="34" width="22" height="9" fill="#ccc"/><rect x="86" y="34" width="22" height="9" fill="#ccc"/><rect x="62" y="46" width="22" height="9" fill="#ccc"/><rect x="86" y="46" width="22" height="9" fill="#ccc"/><text x="23" y="65" font-size="7" fill="#2E7D32" font-family="sans-serif" text-anchor="middle">fast</text><text x="85" y="65" font-size="7" fill="#BE1E2D" font-family="sans-serif" text-anchor="middle">slow</text></svg>',
        'aliases' => ['hick\'s law', 'hicks law', 'hick-zuck law', 'hicks-law'],
    ],

    // --- Layout ---

    [
        'slug' => 'grid-system',
        'name' => 'Grid System',
        'definition' => 'A structure of vertical columns and horizontal rows used to organize content consistently. Grids create alignment, rhythm, and predictability. They don\'t have to be visible — their power is in the invisible order they impose on a layout.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><line x1="10" y1="5" x2="10" y2="75" stroke="#ccc" stroke-width="0.5"/><line x1="37" y1="5" x2="37" y2="75" stroke="#ccc" stroke-width="0.5"/><line x1="64" y1="5" x2="64" y2="75" stroke="#ccc" stroke-width="0.5"/><line x1="91" y1="5" x2="91" y2="75" stroke="#ccc" stroke-width="0.5"/><line x1="110" y1="5" x2="110" y2="75" stroke="#ccc" stroke-width="0.5"/><rect x="10" y="10" width="100" height="12" fill="#000"/><rect x="10" y="28" width="53" height="40" fill="#888"/><rect x="67" y="28" width="43" height="18" fill="#BE1E2D"/><rect x="67" y="50" width="43" height="18" fill="#888"/></svg>',
        'aliases' => ['grid system', 'grid-system', 'grid'],
    ],
    [
        'slug' => 'responsive-design',
        'name' => 'Responsive Design',
        'definition' => 'Design that adapts gracefully to different screen sizes and devices. Not just "make it smaller" — responsive design rethinks layout, typography scale, touch targets, and content priority for each breakpoint. What works on desktop may need a completely different approach on mobile.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="8" width="55" height="40" fill="none" stroke="#000" stroke-width="2"/><rect x="8" y="12" width="24" height="4" fill="#000"/><rect x="8" y="19" width="49" height="3" fill="#888"/><rect x="8" y="24" width="49" height="3" fill="#888"/><rect x="8" y="30" width="49" height="3" fill="#888"/><rect x="75" y="15" width="25" height="42" fill="none" stroke="#000" stroke-width="2"/><rect x="78" y="19" width="19" height="3" fill="#000"/><rect x="78" y="25" width="19" height="2" fill="#888"/><rect x="78" y="29" width="19" height="2" fill="#888"/><rect x="78" y="33" width="19" height="2" fill="#888"/></svg>',
        'aliases' => ['responsive design', 'responsive-design', 'responsive'],
    ],
    [
        'slug' => 'visual-weight',
        'name' => 'Visual Weight',
        'definition' => 'The perceived heaviness or lightness of an element based on its size, color, density, and texture. Dark, large, dense elements feel heavy. Light, small, sparse elements feel airy. Visual weight determines where the eye lands first and how balanced a layout feels.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="15" width="40" height="40" fill="#000"/><circle cx="90" cy="35" r="12" fill="none" stroke="#000" stroke-width="1"/><text x="30" y="70" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">heavy</text><text x="90" y="70" font-size="7" fill="#888" font-family="sans-serif" text-anchor="middle">light</text></svg>',
        'aliases' => ['visual weight', 'visual-weight'],
    ],
    [
        'slug' => 'rhythm',
        'name' => 'Rhythm',
        'definition' => 'The repetition of elements at regular intervals to create a sense of organized movement. Consistent spacing, repeated components, and predictable patterns all establish rhythm. Like music, visual rhythm can be regular (steady) or syncopated (varied but intentional).',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="8" y="25" width="12" height="30" fill="#000"/><rect x="28" y="25" width="12" height="30" fill="#000"/><rect x="48" y="25" width="12" height="30" fill="#000"/><rect x="68" y="25" width="12" height="30" fill="#000"/><rect x="88" y="25" width="12" height="30" fill="#000"/><rect x="108" y="25" width="6" height="30" fill="#ccc"/></svg>',
        'aliases' => ['rhythm'],
    ],
    [
        'slug' => 'unity',
        'name' => 'Unity',
        'definition' => 'The sense that all elements in a composition belong together as a coherent whole. Unity comes from consistent use of color, typography, spacing, and style. A design with strong unity feels intentional and professional; a design lacking unity feels like parts from different kits assembled randomly.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="15" y="10" width="90" height="60" fill="none" stroke="#000" stroke-width="2"/><circle cx="35" cy="30" r="8" fill="#000"/><rect x="50" y="23" width="40" height="5" fill="#000"/><rect x="50" y="32" width="35" height="3" fill="#888"/><circle cx="35" cy="52" r="8" fill="#000"/><rect x="50" y="45" width="40" height="5" fill="#000"/><rect x="50" y="54" width="35" height="3" fill="#888"/></svg>',
        'aliases' => ['unity', 'cohesion'],
    ],

    // --- Readability ---

    [
        'slug' => 'readability',
        'name' => 'Readability',
        'definition' => 'How easily text content can be read and understood. Readability depends on font choice, size, line height, line length, contrast with background, and paragraph spacing. Long lines (over ~75 characters) are hard to track; short line heights make text feel cramped; low contrast strains the eyes.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="8" width="50" height="30" fill="#fff" stroke="#000" stroke-width="1"/><rect x="8" y="12" width="44" height="3" fill="#000"/><rect x="8" y="18" width="44" height="3" fill="#000"/><rect x="8" y="24" width="44" height="3" fill="#000"/><rect x="8" y="30" width="30" height="3" fill="#000"/><rect x="65" y="8" width="50" height="30" fill="#888"/><rect x="68" y="12" width="44" height="2" fill="#999"/><rect x="68" y="16" width="44" height="2" fill="#999"/><rect x="68" y="20" width="44" height="2" fill="#999"/><rect x="68" y="24" width="44" height="2" fill="#999"/><rect x="68" y="28" width="44" height="2" fill="#999"/><rect x="68" y="32" width="30" height="2" fill="#999"/><text x="30" y="55" font-size="7" fill="#2E7D32" font-family="sans-serif" text-anchor="middle">readable</text><text x="90" y="55" font-size="7" fill="#BE1E2D" font-family="sans-serif" text-anchor="middle">strained</text></svg>',
        'aliases' => ['readability', 'legibility'],
    ],

    // --- Composition & Information Design ---

    [
        'slug' => 'composition',
        'name' => 'Composition',
        'definition' => 'The overall arrangement of elements within a frame. Composition determines how the viewer\'s eye enters, moves through, and exits a piece. Strong composition feels intentional and balanced even when asymmetric. Weak composition feels like elements were placed without considering the whole.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="5" width="110" height="70" fill="none" stroke="#000" stroke-width="1.5"/><line x1="42" y1="5" x2="42" y2="75" stroke="#ccc" stroke-width="0.5" stroke-dasharray="3,3"/><line x1="78" y1="5" x2="78" y2="75" stroke="#ccc" stroke-width="0.5" stroke-dasharray="3,3"/><line x1="5" y1="28" x2="115" y2="28" stroke="#ccc" stroke-width="0.5" stroke-dasharray="3,3"/><line x1="5" y1="52" x2="115" y2="52" stroke="#ccc" stroke-width="0.5" stroke-dasharray="3,3"/><circle cx="78" cy="28" r="6" fill="#BE1E2D"/><rect x="10" y="55" width="30" height="15" fill="#000"/><text x="60" y="78" font-size="6" fill="#888" font-family="sans-serif" text-anchor="middle">rule of thirds</text></svg>',
        'aliases' => ['composition'],
    ],
    [
        'slug' => 'data-ink-ratio',
        'name' => 'Data-Ink Ratio',
        'definition' => 'Edward Tufte\'s principle: maximize the share of ink devoted to actual data, and minimize everything else — decorative borders, grid lines, redundant labels, 3D effects. A high data-ink ratio means every mark on the page earns its place. Chartjunk (gratuitous decoration) actively interferes with comprehension.',
        'example' => '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="5" width="50" height="70" fill="#f5f5f5" stroke="#ccc" stroke-width="1"/><line x1="10" y1="65" x2="50" y2="65" stroke="#ccc" stroke-width="0.5"/><line x1="10" y1="50" x2="50" y2="50" stroke="#ccc" stroke-width="0.5"/><line x1="10" y1="35" x2="50" y2="35" stroke="#ccc" stroke-width="0.5"/><line x1="10" y1="20" x2="50" y2="20" stroke="#ccc" stroke-width="0.5"/><rect x="15" y="40" width="8" height="25" fill="#888" stroke="#666" stroke-width="1"/><rect x="27" y="25" width="8" height="40" fill="#888" stroke="#666" stroke-width="1"/><rect x="39" y="50" width="8" height="15" fill="#888" stroke="#666" stroke-width="1"/><rect x="70" y="40" width="8" height="25" fill="#000"/><rect x="82" y="25" width="8" height="40" fill="#000"/><rect x="94" y="50" width="8" height="15" fill="#000"/><text x="30" y="12" font-size="6" fill="#BE1E2D" font-family="sans-serif" text-anchor="middle">noisy</text><text x="85" y="12" font-size="6" fill="#2E7D32" font-family="sans-serif" text-anchor="middle">clean</text></svg>',
        'aliases' => ['data-ink ratio', 'data-ink-ratio', 'data ink ratio'],
    ],
];
