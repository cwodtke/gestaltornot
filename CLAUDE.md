# Gestalt Or Not

AI-powered visual design feedback tool. Analyzes any flat visual — UI screens, sketchnotes, infographics, book covers, posters, slides, data visualizations — using Gestalt principles and format-specific design heuristics.

## Tech Stack
- PHP 8.2 (vanilla, no frameworks, no composer)
- SQLite via PDO (auto-created at `data/gestaltornot.db`)
- Vanilla JS frontend, single-page with modal auth
- Anthropic Claude API for image analysis
- ScreenshotOne API for URL screenshots

## Deployment
- **Host:** iad1-shared-b8-19.dreamhost.com
- **User:** gessy (separate DreamHost user from cwodtke)
- **Web root:** /home/gessy/gestaltornot.com/
- **Deploy all files:**
  ```
  scp *.php *.html *.css *.js *.svg gessy@iad1-shared-b8-19.dreamhost.com:/home/gessy/gestaltornot.com/
  ```
- **SSH key:** Uses `C:/Users/cwodt/.ssh/id_ed25519` (already configured)
- Database auto-creates on first request. No migration steps needed.

## File Structure
| File | Purpose |
|------|---------|
| `index.html` | Main page — upload area, auth modal, review history |
| `script.js` | Frontend logic — upload, URL input, auth, review history, rate limiting, glossary term linking |
| `style.css` | Bauhaus modernist styles (black/white/red, DM Sans) |
| `api.php` | Sends images to Claude API, returns design feedback |
| `auth.php` | All auth endpoints via `?action=` (signup, signin, signout, me, forgot, reset, reviews, delete_review, usage) |
| `save.php` | Saves reviews to SQLite + image file to `uploads/`, associates with user if signed in |
| `view.php` | Displays shared reviews (checks DB first, JSON fallback for legacy, glossary term linking) |
| `db.php` | SQLite connection + schema auto-creation (includes `image_path` migration) |
| `config.php` | API keys (Anthropic + ScreenshotOne) — DO NOT commit |
| `glossary-data.php` | Design term data — 32 terms with slugs, definitions, SVG illustrations, aliases |
| `glossary.php` | Design glossary page — TOC chips, anchored terms, inline SVGs |
| `reset.html` | Password reset landing page (linked from email) |
| `data/.htaccess` | Blocks direct access to SQLite database file |
| `data/gestaltornot.db` | SQLite database (auto-created, not in repo) |
| `reviews/` | Legacy JSON review files (pre-auth, still readable by view.php) |
| `uploads/` | Saved design images as PNG files, named `{review_id}.png` |

## Auth System
- PHP sessions for login state
- `password_hash()` / `password_verify()` (bcrypt)
- Password reset via PHP `mail()` from noreply@gestaltornot.com
- Admin email: `cwodtke@stanford.edu` (unlimited analyses)

## Rate Limiting
- **Anonymous users:** 5/day, tracked client-side (localStorage)
- **Signed-in users:** 10/day, tracked server-side (counts reviews in DB for today)
- **Admin (cwodtke@stanford.edu):** Unlimited — `auth.php?action=usage` returns `max: null`
- Anonymous rate limit message nudges toward sign-in ("Sign in for 10/day")
- PayPal tip jar link in rate limit message and footer

## Image Storage
- Design images saved to `uploads/{review_id}.png` on disk
- `image_path` column in reviews table stores relative path (e.g. `uploads/abc123.png`)
- `save.php` handles both data URL and raw base64 input (strips prefix if present)
- `view.php` prefers file path, falls back to inline base64 `screenshot` column for legacy reviews
- OG meta tags use public URL to saved file for social sharing

## Design — "Modular Grid" (Bauhaus / Swiss-modernist)
Redesigned from the old burgundy/teal "Constructivist Poster" to a Müller-Brockmann modular grid: off-white paper ground, thin black rules, primary Bauhaus accents. No rounded corners except circles; no shadows except the modal.
- CSS variables: `--ink` (#111 text & rules), `--paper` (#FBFBF9), `--red` (#D8362A), `--blue` (#1D52B5), `--yellow` (#F2B600), `--yellow-text` (#C99500, darkened for legibility on paper), `--drag` (#F0EEE6 drag-over fill), `--hair` (rgba(17,17,17,.12) faint row rule), `--rule-soft`.
- Fonts: **Archivo Black** (`--display` — H1, numerals) and **Space Grotesk** (`--font` — body/UI/labels). Label convention: 11px, uppercase, letter-spacing .14em, opacity .55.
- Borders: 1px solid ink hairlines; 3px solid ink structural separators (`.sep-top`/`.sep-bottom`).
- Brand mark: `.mark` = red circle / blue square / yellow triangle (`.mark-sm` for the 11px About-header variant).
- Homepage (`index.html`) is one bordered grid (`.board.board-hero`, fills viewport): meta row → hero band → work region (uploader cell + about/critique cell) → footer band. Results, rate-limit, and review history flow below in `main.flow` (max 1100px, centered). Analyze auto-scrolls to results since the hero is full-viewport.
- Secondary pages (`glossary.php`, `view.php`, `reset.html`) use a compact `.site-bar` grid header + `main.flow` + `.site-footer`. Glossary keeps its page-specific styles inline.
- Site name displays as "Gestalt Or Not" (with spaces); secondary-page wordmark links back to `/`.

## Key Decisions

### Auto-save on analysis (not on share)
Saving a review to "My Reviews" and sharing it publicly are separate concerns. Signed-in users get their review auto-saved the moment analysis completes — no need to click Share. The Share button just reveals the URL of the already-saved review (avoids duplicates via `lastSavedId` tracking). Anonymous users still save on Share.

### Sign-in mid-session saves current review
If a user analyzes something while anonymous, then signs in from the "Sign in to save" prompt, the current review auto-saves immediately and My Reviews loads — giving clear feedback the sign-in worked.

### Thumbnail grid over text list for My Reviews
Replaced the old text-based review list (URL + date + preview text) with a visual grid of clickable thumbnails. 3 columns on desktop, 2 on mobile. 4:3 aspect ratio with `object-fit: cover`. Placeholder cards (showing hostname or "Image") for legacy reviews without saved images.

### Delete capability on reviews
Users can delete reviews from their grid — X button appears on hover, turns red, requires confirm() dialog. Deletes both the DB record and the image file from `uploads/`.

### Upload area resets after analysis
After analysis completes, the upload box resets to the clean "+" drop zone so users can immediately start a new analysis without confusion.

### Tip jar over paid tiers
PayPal donate link (hosted_button_id=QRDU77Z7K56XG) in footer and rate limit message. Decided against Stripe/paid model because: API costs per analysis are pennies, Stripe transaction overhead makes micro-payments uneconomical, friction kills reach for a teaching tool, and the engineering complexity isn't justified yet. Revisit if traffic demands it.

### Tiered rate limits over flat limit
Anonymous 5/day, signed-in 10/day. Incentivizes account creation (grows email list) without paywalling. Admin bypass for testing.

## Glossary System

### How it works
Claude's feedback uses `*term*` italic markers for design vocabulary. Both client-side (script.js `formatFeedback`) and server-side (view.php `formatFeedbackPHP`) convert matched terms into clickable links to `glossary.php#slug`. Unmatched terms stay as plain `<em>` — no regression.

### Architecture
- `glossary-data.php` — PHP array, single source of truth for term definitions, SVG illustrations, and alias lists
- `script.js` — `GLOSSARY_ALIASES` JS object (lowercase alias → slug), duplicated from PHP intentionally. 32 terms don't justify async fetch or PHP-in-JS embedding.
- `view.php` — Loads aliases from `glossary-data.php` at runtime via `require`, uses `preg_replace_callback` for server-side linking
- `glossary.php` — Renders all terms grouped by section (Gestalt, Visual Design, Interaction/UX, Layout, Readability, Composition & Information Design)

### Adding a new term
1. Add entry to `glossary-data.php` (slug, name, definition, SVG example, aliases array)
2. Add aliases to `GLOSSARY_ALIASES` in `script.js`
3. Add slug to the appropriate `$sections` array in `glossary.php`
4. Deploy all three files

### Styling
`.design-term-link` — subtle underline, darkens on hover, inherits italic from child `.design-term`. Glossary page uses inline `<style>` to avoid bloating style.css.

## Analysis Prompt (api.php)

### Format-aware design
The system prompt detects 7 visual format categories: screen design, information design, visual thinking (sketchnotes), print/editorial, marketing, presentations, other. It applies universal design checks (hierarchy, proximity, contrast, alignment, typography, whitespace, unity, color, composition) to everything, then adds format-specific checks based on what it detects.

### Key prompt behaviors
- Identifies format first, adjusts criteria accordingly
- Uses `*term*` italic markers for design vocabulary (feeds into glossary linking)
- Confidence levels: "clear problem" / "worth reconsidering" / "just something to think about"
- Output format: TL;DR with 3 priorities + 1 working-well, then detailed sections in `**TOPIC IN CAPS**` format
- `formatFeedback` in script.js parses this structure into styled HTML sections
