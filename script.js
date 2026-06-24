// GestaltOrNot — Frontend Logic

// Glossary alias map: lowercase alias → slug
const GLOSSARY_ALIASES = {
    'proximity': 'proximity',
    'similarity': 'similarity',
    'continuity': 'continuity',
    'closure': 'closure',
    'figure-ground': 'figure-ground',
    'figure ground': 'figure-ground',
    'common region': 'common-region',
    'common-region': 'common-region',
    'common fate': 'common-fate',
    'common-fate': 'common-fate',
    'visual hierarchy': 'visual-hierarchy',
    'visual-hierarchy': 'visual-hierarchy',
    'hierarchy': 'visual-hierarchy',
    'contrast': 'contrast',
    'alignment': 'alignment',
    'repetition': 'repetition',
    'consistency': 'repetition',
    'whitespace': 'whitespace',
    'white space': 'whitespace',
    'negative space': 'whitespace',
    'balance': 'balance',
    'symmetry': 'balance',
    'emphasis': 'emphasis',
    'focal point': 'emphasis',
    'scale': 'scale',
    'color theory': 'color-theory',
    'color-theory': 'color-theory',
    'colour theory': 'color-theory',
    'color': 'color-theory',
    'typography': 'typography',
    'type': 'typography',
    'typographic': 'typography',
    'affordance': 'affordance',
    'affordances': 'affordance',
    'feedback': 'feedback',
    'mapping': 'mapping',
    'constraint': 'constraint',
    'constraints': 'constraint',
    'progressive disclosure': 'progressive-disclosure',
    'progressive-disclosure': 'progressive-disclosure',
    "fitts's law": 'fitts-law',
    "fitts' law": 'fitts-law',
    'fitts law': 'fitts-law',
    "fitt's law": 'fitts-law',
    'fitts-law': 'fitts-law',
    "hick's law": 'hicks-law',
    'hicks law': 'hicks-law',
    'hick-zuck law': 'hicks-law',
    'hicks-law': 'hicks-law',
    'grid system': 'grid-system',
    'grid-system': 'grid-system',
    'grid': 'grid-system',
    'responsive design': 'responsive-design',
    'responsive-design': 'responsive-design',
    'responsive': 'responsive-design',
    'visual weight': 'visual-weight',
    'visual-weight': 'visual-weight',
    'rhythm': 'rhythm',
    'unity': 'unity',
    'cohesion': 'unity',
    'readability': 'readability',
    'legibility': 'readability',
    'composition': 'composition',
    'data-ink ratio': 'data-ink-ratio',
    'data-ink-ratio': 'data-ink-ratio',
    'data ink ratio': 'data-ink-ratio',
};

document.addEventListener('DOMContentLoaded', function() {

// --- Auth Module ---
const authBar = document.getElementById('authBar');
const userBar = document.getElementById('userBar');
const userEmail = document.getElementById('userEmail');
const authModal = document.getElementById('authModal');
const authForm = document.getElementById('authForm');
const modalClose = document.getElementById('modalClose');

let isSignedIn = false;

// Check auth state on page load
fetch('auth.php?action=me').then(r => r.json()).then(data => {
    if (data.signed_in) {
        isSignedIn = true;
        showSignedIn(data.email);
    }
}).catch(() => {});

function showSignedIn(email) {
    isSignedIn = true;
    authBar.classList.add('hidden');
    userBar.classList.remove('hidden');
    userEmail.textContent = email;

    // Remove "sign in to save" prompt if visible
    const prompt = document.querySelector('.signin-prompt');
    if (prompt) prompt.remove();

    // Auto-save current review if there's unsaved feedback
    if (lastFeedback && !lastSavedId) {
        autoSaveReview().then(() => loadReviewHistory());
    } else {
        loadReviewHistory();
    }
}

function showSignedOut() {
    isSignedIn = false;
    authBar.classList.remove('hidden');
    userBar.classList.add('hidden');
    userEmail.textContent = '';
    document.getElementById('reviewHistory').classList.add('hidden');
}

function openModal(content) {
    authForm.innerHTML = content;
    authModal.classList.remove('hidden');
}

function closeModal() {
    authModal.classList.add('hidden');
    authForm.innerHTML = '';
}

modalClose.addEventListener('click', closeModal);
authModal.addEventListener('click', (e) => {
    if (e.target === authModal) closeModal();
});

document.getElementById('signInBtn').addEventListener('click', () => showSignInForm());
document.getElementById('signUpBtn').addEventListener('click', () => showSignUpForm());
document.getElementById('signOutBtn').addEventListener('click', async () => {
    await fetch('auth.php?action=signout', { method: 'POST' });
    showSignedOut();
});
document.getElementById('myReviewsBtn').addEventListener('click', loadReviewHistory);

function showSignInForm() {
    openModal(`
        <div class="auth-form">
            <h2>Sign In</h2>
            <div id="authError" class="auth-error hidden"></div>
            <input type="email" id="authEmail" placeholder="Email" autocomplete="email">
            <input type="password" id="authPassword" placeholder="Password" autocomplete="current-password">
            <button class="btn" id="authSubmit">Sign In</button>
            <p class="auth-toggle">
                <button id="showForgot">Forgot password?</button>
                &middot; No account? <button id="switchToSignUp">Sign Up</button>
            </p>
        </div>
    `);
    document.getElementById('authSubmit').addEventListener('click', submitSignIn);
    document.getElementById('authEmail').addEventListener('keydown', (e) => { if (e.key === 'Enter') document.getElementById('authPassword').focus(); });
    document.getElementById('authPassword').addEventListener('keydown', (e) => { if (e.key === 'Enter') submitSignIn(); });
    document.getElementById('switchToSignUp').addEventListener('click', showSignUpForm);
    document.getElementById('showForgot').addEventListener('click', showForgotForm);
}

function showSignUpForm() {
    openModal(`
        <div class="auth-form">
            <h2>Sign Up</h2>
            <div id="authError" class="auth-error hidden"></div>
            <input type="email" id="authEmail" placeholder="Email" autocomplete="email">
            <input type="password" id="authPassword" placeholder="Password (8+ characters)" autocomplete="new-password">
            <button class="btn" id="authSubmit">Sign Up</button>
            <p class="auth-toggle">
                Already have an account? <button id="switchToSignIn">Sign In</button>
            </p>
        </div>
    `);
    document.getElementById('authSubmit').addEventListener('click', submitSignUp);
    document.getElementById('authEmail').addEventListener('keydown', (e) => { if (e.key === 'Enter') document.getElementById('authPassword').focus(); });
    document.getElementById('authPassword').addEventListener('keydown', (e) => { if (e.key === 'Enter') submitSignUp(); });
    document.getElementById('switchToSignIn').addEventListener('click', showSignInForm);
}

function showForgotForm() {
    openModal(`
        <div class="auth-form">
            <h2>Reset Password</h2>
            <div id="authError" class="auth-error hidden"></div>
            <div id="authSuccess" class="auth-success hidden"></div>
            <p class="subtle" style="margin-bottom: 1rem;">Enter your email and we'll send a reset link.</p>
            <input type="email" id="authEmail" placeholder="Email" autocomplete="email">
            <button class="btn" id="authSubmit">Send Reset Link</button>
            <p class="auth-toggle">
                <button id="switchToSignIn">Back to Sign In</button>
            </p>
        </div>
    `);
    document.getElementById('authSubmit').addEventListener('click', submitForgot);
    document.getElementById('authEmail').addEventListener('keydown', (e) => { if (e.key === 'Enter') submitForgot(); });
    document.getElementById('switchToSignIn').addEventListener('click', showSignInForm);
}

async function submitSignIn() {
    const email = document.getElementById('authEmail').value.trim();
    const password = document.getElementById('authPassword').value;
    const errorEl = document.getElementById('authError');
    errorEl.classList.add('hidden');

    const res = await fetch('auth.php?action=signin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    const data = await res.json();

    if (data.error) {
        errorEl.textContent = data.error;
        errorEl.classList.remove('hidden');
        return;
    }

    showSignedIn(data.email);
    closeModal();
}

async function submitSignUp() {
    const email = document.getElementById('authEmail').value.trim();
    const password = document.getElementById('authPassword').value;
    const errorEl = document.getElementById('authError');
    errorEl.classList.add('hidden');

    const res = await fetch('auth.php?action=signup', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    const data = await res.json();

    if (data.error) {
        errorEl.textContent = data.error;
        errorEl.classList.remove('hidden');
        return;
    }

    showSignedIn(data.email);
    closeModal();
}

async function submitForgot() {
    const email = document.getElementById('authEmail').value.trim();
    const errorEl = document.getElementById('authError');
    const successEl = document.getElementById('authSuccess');
    errorEl.classList.add('hidden');
    successEl.classList.add('hidden');

    if (!email) {
        errorEl.textContent = 'Please enter your email';
        errorEl.classList.remove('hidden');
        return;
    }

    await fetch('auth.php?action=forgot', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
    });

    successEl.textContent = 'If that email has an account, a reset link has been sent.';
    successEl.classList.remove('hidden');
    document.getElementById('authSubmit').disabled = true;
    document.getElementById('authSubmit').textContent = 'Sent';
}

async function loadReviewHistory() {
    const historySection = document.getElementById('reviewHistory');
    const reviewList = document.getElementById('reviewList');

    historySection.classList.remove('hidden');
    historySection.scrollIntoView({ behavior: 'smooth' });
    reviewList.innerHTML = '<p class="subtle">Loading...</p>';

    const res = await fetch('auth.php?action=reviews');
    const data = await res.json();

    if (data.error) {
        reviewList.innerHTML = '<p class="subtle">Could not load reviews.</p>';
        return;
    }

    if (!data.reviews || data.reviews.length === 0) {
        reviewList.innerHTML = '<p class="subtle">No reviews yet. Analyze a design to get started.</p>';
        return;
    }

    reviewList.innerHTML = '<div class="review-grid">' + data.reviews.map(r => {
        const thumbSrc = r.image_path || null;
        const dateStr = r.created_at ? r.created_at.split(' ')[0] : '';
        return `
            <div class="review-thumb-wrap">
                <button class="review-delete" data-id="${escapeHtml(r.id)}" title="Delete review">&times;</button>
                <a href="view.php?id=${encodeURIComponent(r.id)}" class="review-thumb">
                    ${thumbSrc
                        ? `<img src="${escapeHtml(thumbSrc)}" alt="Design thumbnail" loading="lazy">`
                        : `<div class="review-thumb-placeholder">${r.url ? escapeHtml(new URL(r.url).hostname) : 'Image'}</div>`
                    }
                    <span class="review-thumb-date">${escapeHtml(dateStr)}</span>
                </a>
            </div>
        `;
    }).join('') + '</div>';

    // Attach delete handlers
    reviewList.querySelectorAll('.review-delete').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            if (!confirm('Delete this review?')) return;

            const id = btn.dataset.id;
            btn.disabled = true;

            const res = await fetch('auth.php?action=delete_review', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const result = await res.json();

            if (result.success) {
                btn.closest('.review-thumb-wrap').remove();
                // If grid is now empty, show empty message
                const grid = reviewList.querySelector('.review-grid');
                if (grid && grid.children.length === 0) {
                    reviewList.innerHTML = '<p class="subtle">No reviews yet. Analyze a design to get started.</p>';
                }
            } else {
                btn.disabled = false;
            }
        });
    });
}

// --- End Auth Module ---

const uploadBox = document.getElementById('uploadBox');
const urlBox = document.getElementById('urlBox');
const fileInput = document.getElementById('fileInput');
const urlInput = document.getElementById('urlInput');
const preview = document.getElementById('preview');
const analyzeBtn = document.getElementById('analyzeBtn');
const results = document.getElementById('results');
const loading = document.getElementById('loading');
const feedback = document.getElementById('feedback');
const rateLimit = document.getElementById('rateLimit');
const toggleBtns = document.querySelectorAll('.toggle-btn');

let selectedFile = null;
let inputMode = 'upload'; // 'upload' or 'url'
let lastFeedback = null;
let lastScreenshot = null;
let lastUrl = null;
let lastImageBase64 = null; // tracks the image data URL for saving to disk
let lastSavedId = null; // set when auto-saved for signed-in users

// Toggle between upload and URL modes
toggleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const mode = btn.dataset.mode;
        if (mode === inputMode) return;

        inputMode = mode;
        toggleBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        if (mode === 'upload') {
            uploadBox.classList.remove('hidden');
            urlBox.classList.add('hidden');
            // Show analyze button if file is selected
            if (selectedFile) {
                analyzeBtn.classList.remove('hidden');
            } else {
                analyzeBtn.classList.add('hidden');
            }
        } else {
            uploadBox.classList.add('hidden');
            urlBox.classList.remove('hidden');
            analyzeBtn.classList.remove('hidden');
        }

        // Reset results
        results.classList.add('hidden');
        feedback.innerHTML = '';
    });
});

// Click to upload
uploadBox.addEventListener('click', () => {
    if (!selectedFile) {
        fileInput.click();
    }
});

// File selected
fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        handleFile(file);
    }
});

// Drag and drop
uploadBox.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadBox.classList.add('drag-over');
});

uploadBox.addEventListener('dragleave', () => {
    uploadBox.classList.remove('drag-over');
});

uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadBox.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        handleFile(file);
    }
});

function handleFile(file) {
    // Reject files over 10MB (matches server-side limit in api.php)
    if (file.size > 10 * 1024 * 1024) {
        alert('Image too large (max 10MB). Please resize or compress it first.');
        return;
    }

    selectedFile = file;

    // Show preview
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.src = e.target.result;
        lastImageBase64 = e.target.result; // store data URL for saving
        preview.classList.remove('hidden');
        uploadBox.classList.add('has-image');
        analyzeBtn.classList.remove('hidden');
    };
    reader.readAsDataURL(file);

    // Reset results
    results.classList.add('hidden');
    feedback.innerHTML = '';
}

// Allow re-upload by clicking preview
preview.addEventListener('click', (e) => {
    e.stopPropagation();
    fileInput.click();
});

// URL input - show analyze button when URL entered
urlInput.addEventListener('input', () => {
    // Button is always visible in URL mode
});

// Analyze button
analyzeBtn.addEventListener('click', async () => {
    // Check rate limit — server-side for signed-in, localStorage for anonymous
    if (isSignedIn) {
        try {
            const usageRes = await fetch('auth.php?action=usage');
            const usageData = await usageRes.json();
            if (usageData.max !== null && usageData.count >= usageData.max) {
                document.getElementById('rateLimitMsg').textContent = "You've used your 10 analyses for today.";
                document.getElementById('rateLimitSub').innerHTML = 'Come back tomorrow, or <a href="https://www.paypal.com/donate/?hosted_button_id=QRDU77Z7K56XG" target="_blank" rel="noopener" class="support-link">support the project</a>.';
                rateLimit.classList.remove('hidden');
                return;
            }
        } catch (e) {
            // If check fails, let them through
        }
    } else {
        const today = new Date().toDateString();
        const usage = JSON.parse(localStorage.getItem('gestaltUsage') || '{}');
        if (usage.date !== today) {
            usage.date = today;
            usage.count = 0;
        }
        if (usage.count >= 5) {
            document.getElementById('rateLimitMsg').textContent = "You've used your 5 free analyses for today.";
            document.getElementById('rateLimitSub').innerHTML = '<a href="#" id="rateLimitSignIn" class="support-link">Sign in</a> for 10/day, or come back tomorrow.';
            rateLimit.classList.remove('hidden');
            // Attach sign-in handler
            const signInLink = document.getElementById('rateLimitSignIn');
            if (signInLink) signInLink.addEventListener('click', (e) => { e.preventDefault(); showSignInForm(); });
            return;
        }
    }

    // Validate input based on mode
    if (inputMode === 'upload' && !selectedFile) {
        return;
    }

    if (inputMode === 'url') {
        let url = urlInput.value.trim();
        if (!url) {
            alert('Please enter a URL');
            return;
        }
        // Auto-add https:// if missing
        if (!url.startsWith('http://') && !url.startsWith('https://')) {
            url = 'https://' + url;
            urlInput.value = url;
        }
    }

    // Show loading
    results.classList.remove('hidden');
    loading.classList.remove('hidden');
    results.scrollIntoView({ behavior: 'smooth', block: 'start' });
    feedback.innerHTML = '';
    analyzeBtn.disabled = true;
    analyzeBtn.textContent = inputMode === 'url' ? 'Capturing screenshot...' : 'Analyzing...';

    try {
        let requestBody;

        if (inputMode === 'upload') {
            // Convert file to base64
            const base64 = await fileToBase64(selectedFile);
            requestBody = {
                image: base64,
                filename: selectedFile.name
            };
        } else {
            // Send URL for screenshot
            requestBody = {
                url: urlInput.value.trim()
            };
            // Update loading text after a moment
            setTimeout(() => {
                if (analyzeBtn.disabled) {
                    analyzeBtn.textContent = 'Analyzing...';
                }
            }, 3000);
        }

        // Send to backend
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        // Update usage count for anonymous users
        if (!isSignedIn) {
            const today = new Date().toDateString();
            const usage = JSON.parse(localStorage.getItem('gestaltUsage') || '{}');
            if (usage.date !== today) { usage.date = today; usage.count = 0; }
            usage.count++;
            localStorage.setItem('gestaltUsage', JSON.stringify(usage));
        }

        // Store for sharing
        lastFeedback = data.feedback;
        lastScreenshot = data.screenshot || null;
        lastUrl = inputMode === 'url' ? urlInput.value.trim() : null;
        lastSavedId = null;

        // For URL mode, capture screenshot as image for saving
        if (data.screenshot) {
            lastImageBase64 = data.screenshot;
        }

        // Auto-save for signed-in users
        if (isSignedIn) {
            autoSaveReview();
        }

        // Display results — use screenshot from API (URL mode) or the uploaded image
        displayFeedback(data.feedback, data.screenshot || lastImageBase64);

    } catch (error) {
        feedback.innerHTML = `
            <div class="feedback-section">
                <h3>Error</h3>
                <p>${error.message || 'Something went wrong. Please try again.'}</p>
            </div>
        `;
    } finally {
        loading.classList.add('hidden');
        analyzeBtn.disabled = false;
        analyzeBtn.textContent = 'Analyze Design';

        // Reset upload area for next analysis
        selectedFile = null;
        preview.classList.add('hidden');
        preview.src = '';
        uploadBox.classList.remove('has-image');
        fileInput.value = '';
        urlInput.value = '';
    }
});

function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => {
            // Remove data URL prefix, keep just base64
            const base64 = reader.result.split(',')[1];
            resolve(base64);
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

function displayFeedback(text, screenshot) {
    // Parse the feedback text and format it nicely
    // The API returns markdown-ish text, we'll convert to HTML

    let html = '';

    // Share button at top
    html += `<div class="share-section">
        <button id="shareBtn" class="btn btn-secondary">Share This Review</button>
        <div id="shareResult" class="share-result hidden"></div>
    </div>`;

    // Show the analyzed image
    if (screenshot) {
        html += `<div class="screenshot-preview">
            <img src="${screenshot}" alt="Design analyzed">
        </div>`;
    }

    html += formatFeedback(text);

    // Sign-in prompt for anonymous users
    if (!isSignedIn) {
        html += `<div class="signin-prompt">
            <p><button id="promptSignIn" class="auth-link">Sign in</button> to save this review to your account.</p>
        </div>`;
    }

    feedback.innerHTML = html;

    // Attach share button handler
    document.getElementById('shareBtn').addEventListener('click', handleShare);

    // Attach sign-in prompt handler
    const promptBtn = document.getElementById('promptSignIn');
    if (promptBtn) {
        promptBtn.addEventListener('click', showSignInForm);
    }
}

async function autoSaveReview() {
    try {
        const response = await fetch('save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                feedback: lastFeedback,
                screenshot: lastScreenshot,
                url: lastUrl,
                image: lastImageBase64
            })
        });
        const data = await response.json();
        if (data.success) {
            lastSavedId = data.id;
        }
    } catch (e) {
        // Silent fail — share button still works as fallback
    }
}

async function handleShare() {
    const shareBtn = document.getElementById('shareBtn');
    const shareResult = document.getElementById('shareResult');

    shareBtn.disabled = true;
    shareBtn.textContent = 'Saving...';

    try {
        let shareUrl;

        if (lastSavedId) {
            // Already auto-saved — just show the link
            shareUrl = 'https://gestaltornot.com/view.php?id=' + lastSavedId;
        } else {
            // Not signed in or auto-save failed — save now
            const response = await fetch('save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    feedback: lastFeedback,
                    screenshot: lastScreenshot,
                    url: lastUrl,
                    image: lastImageBase64
                })
            });
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            shareUrl = data.share_url;
            lastSavedId = data.id;
        }

        // Show share link
        shareResult.innerHTML = `
            <input type="text" id="shareLink" value="${shareUrl}" readonly>
            <button id="copyBtn" class="btn btn-small">Copy</button>
        `;
        shareResult.classList.remove('hidden');
        shareBtn.classList.add('hidden');

        // Copy button handler
        document.getElementById('copyBtn').addEventListener('click', () => {
            const linkInput = document.getElementById('shareLink');
            linkInput.select();
            document.execCommand('copy');
            document.getElementById('copyBtn').textContent = 'Copied!';
        });

    } catch (error) {
        shareResult.innerHTML = `<p class="error">Failed to save: ${error.message}</p>`;
        shareResult.classList.remove('hidden');
        shareBtn.disabled = false;
        shareBtn.textContent = 'Share This Review';
    }
}

function formatFeedback(text) {
    // Split into sections based on ** headers **
    let html = '';

    // First, handle the TL;DR / quick take section at the top
    const tldrMatch = text.match(/^(.*?Here's the quick take:[\s\S]*?)(?=\n\n\*\*[A-Z]|\n---)/i);

    if (tldrMatch) {
        const tldr = tldrMatch[1];
        const priorities = tldr.match(/- \*\*Priority \d+:\*\* .+/g) || [];
        const working = tldr.match(/- \*\*Working well:\*\* .+/g) || [];

        html += '<div class="feedback-header">';
        html += '<h2>Quick Take</h2>';
        html += '<ul class="priorities">';

        priorities.forEach(p => {
            const content = p.replace(/- \*\*Priority \d+:\*\* /, '');
            html += `<li>${escapeHtml(content)}</li>`;
        });

        working.forEach(w => {
            const content = w.replace(/- \*\*Working well:\*\* /, '');
            html += `<li class="working">${escapeHtml(content)}</li>`;
        });

        html += '</ul></div>';
    }

    // Then handle each section
    const sections = text.split(/\n\*\*([A-Z][A-Z\s&]+)\*\*\n/);

    for (let i = 1; i < sections.length; i += 2) {
        const title = sections[i];
        const content = sections[i + 1] || '';

        if (title && content.trim()) {
            html += '<div class="feedback-section">';
            html += `<h3>${escapeHtml(title)}</h3>`;

            // Format paragraphs and italic terms (link to glossary if matched)
            let formatted = escapeHtml(content.trim());
            formatted = formatted.replace(/\*([^*]+)\*/g, function(match, term) {
                const slug = GLOSSARY_ALIASES[term.toLowerCase()];
                if (slug) {
                    return '<a href="/glossary.php#' + slug + '" target="_blank" class="design-term-link"><em class="design-term">' + term + '</em></a>';
                }
                return '<em class="design-term">' + term + '</em>';
            });
            formatted = formatted.split('\n\n').map(p => `<p>${p}</p>`).join('');

            html += formatted;
            html += '</div>';
        }
    }

    // If no structured content found, just show as paragraphs
    if (html === '') {
        html = '<div class="feedback-section">';
        html += text.split('\n\n').map(p => `<p>${escapeHtml(p)}</p>`).join('');
        html += '</div>';
    }

    return html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

}); // end DOMContentLoaded
