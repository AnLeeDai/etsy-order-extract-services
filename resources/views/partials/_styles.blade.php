<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html {
        -webkit-font-smoothing: antialiased;
        scroll-behavior: smooth;
    }

    :root {
        --font: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        --bg: #f1f5f9;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --border-focus: #6366f1;
        --ink: #0f172a;
        --ink-2: #334155;
        --ink-3: #64748b;
        --accent: #6366f1;
        --accent-h: #4f46e5;
        --accent-soft: #eef2ff;
        --accent-text: #4338ca;
        --green: #059669;
        --green-soft: #d1fae5;
        --green-text: #065f46;
        --red: #dc2626;
        --red-soft: #fee2e2;
        --s0: 0 0 0 1px rgb(0 0 0/.04);
        --s1: 0 1px 3px rgb(0 0 0/.08), 0 1px 2px rgb(0 0 0/.05);
        --s2: 0 4px 12px rgb(0 0 0/.08), 0 1px 4px rgb(0 0 0/.05);
        --s3: 0 12px 32px rgb(0 0 0/.10), 0 2px 8px rgb(0 0 0/.06);
        --radius-sm: .5rem;
        --radius: .75rem;
        --radius-lg: 1rem;
    }

    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--ink-2);
        min-height: 100vh;
    }

    /* ── Top bar ── */
    .topbar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        box-shadow: 0 1px 0 rgb(0 0 0/.1), var(--s2);
        padding: 0 1.5rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .topbar-brand {
        display: flex;
        align-items: center;
        gap: .625rem;
        text-decoration: none;
    }

    .topbar-icon {
        width: 34px;
        height: 34px;
        background: rgb(255 255 255/.18);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .topbar-icon svg {
        width: 18px;
        height: 18px;
    }

    .topbar-brand span {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.01em;
    }

    .topbar-right { display: flex; align-items: center; gap: .75rem; }

    .topbar-badge {
        background: rgb(255 255 255/.2);
        color: #fff;
        font-size: .6875rem;
        font-weight: 600;
        padding: .25rem .625rem;
        border-radius: 9999px;
        letter-spacing: .03em;
    }

    .topbar-support-btn {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        background: rgb(255 255 255/.12);
        color: rgb(255 255 255/.9);
        font-size: .8125rem;
        font-weight: 600;
        padding: .375rem .875rem;
        border-radius: 9999px;
        text-decoration: none;
        transition: background .2s, color .2s;
        letter-spacing: .01em;
        border: 1px solid rgb(255 255 255/.18);
    }

    .topbar-support-btn:hover,
    .topbar-support-btn.active {
        background: rgb(255 255 255/.25);
        color: #fff;
    }

    .topbar-support-btn svg { flex-shrink: 0; }

    /* ── Layout ── */
    .page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 1.5rem 1rem 3rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    @media(min-width:768px) {
        .page {
            padding: 2rem 1.75rem 4rem;
        }
    }

    /* ── Upload card ── */
    .upload-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--s1);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .upload-card-header {
        padding: 1.25rem 1.5rem 0;
    }

    .upload-card-header h2 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--ink);
    }

    .upload-card-header p {
        margin-top: .25rem;
        font-size: .875rem;
        color: var(--ink-3);
    }

    .upload-card-body {
        padding: 1.25rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* ── Drop zone ── */
    .drop-zone {
        position: relative;
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        background: var(--surface-2);
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s, padding .2s;
        margin-bottom: 1rem;
    }

    .drop-zone:hover,
    .drop-zone.drag-over {
        border-color: var(--accent);
        background: var(--accent-soft);
    }

    .drop-zone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .drop-zone-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--accent-soft), #e0e7ff);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: opacity .2s;
    }

    .drop-zone-icon svg {
        width: 24px;
        height: 24px;
        color: var(--accent);
    }

    .drop-zone-text {
        font-size: .9375rem;
        font-weight: 600;
        color: var(--ink);
        transition: opacity .2s;
    }

    .drop-zone-sub {
        margin-top: .375rem;
        font-size: .8125rem;
        color: var(--ink-3);
        transition: opacity .2s;
    }

    .drop-zone-sub strong {
        color: var(--accent-text);
        font-weight: 600;
    }

    /* compact state after files selected */
    .drop-zone.has-files {
        padding: .75rem 1rem;
        border-style: solid;
        border-color: var(--green);
        background: #f0fdf4;
    }

    .drop-zone.has-files:hover,
    .drop-zone.has-files.drag-over {
        border-color: var(--green);
        background: var(--green-soft);
    }

    .drop-zone.has-files .drop-zone-icon,
    .drop-zone.has-files .drop-zone-text,
    .drop-zone.has-files .drop-zone-sub {
        display: none;
    }

    .drop-zone-compact {
        display: none;
        align-items: center;
        gap: .625rem;
        pointer-events: none;
    }

    .drop-zone.has-files .drop-zone-compact {
        display: flex;
    }

    .drop-zone-compact-icon {
        width: 28px;
        height: 28px;
        background: var(--green-soft);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .drop-zone-compact-icon svg {
        width: 14px;
        height: 14px;
        color: var(--green);
    }

    .drop-zone-compact-label {
        font-size: .875rem;
        font-weight: 600;
        color: var(--green-text);
    }

    .drop-zone-compact-hint {
        font-size: .8125rem;
        color: var(--ink-3);
        margin-left: .25rem;
    }

    /* ── File list ── */
    #file-list {
        display: none;
        flex-direction: column;
        gap: .5rem;
        margin: 1rem;
    }

    #file-list.visible {
        display: flex;
    }

    .file-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .75rem;
        font-weight: 600;
        color: var(--ink-3);
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .file-chip {
        display: flex;
        align-items: center;
        gap: .625rem;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .5rem .75rem;
        font-size: .8125rem;
    }

    .file-chip-icon {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #fecaca, #fed7aa);
        border-radius: 6px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .file-chip-icon svg {
        width: 14px;
        height: 14px;
        color: #b91c1c;
    }

    .file-chip-name {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 500;
        color: var(--ink);
    }

    .file-chip-size {
        font-size: .75rem;
        color: var(--ink-3);
        flex-shrink: 0;
    }

    .file-chip-remove {
        margin-left: auto;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border: none;
        background: transparent;
        color: var(--ink-3);
        border-radius: 4px;
        cursor: pointer;
        transition: background .15s, color .15s;
        padding: 0;
    }

    .file-chip-remove:hover {
        background: var(--red-soft);
        color: var(--red);
    }

    .file-chip-remove svg {
        width: 12px;
        height: 12px;
        pointer-events: none;
    }

    .file-chips {
        display: flex;
        flex-direction: column;
        gap: .375rem;
        margin-top: .5rem;
    }

    /* ── Submit row ── */
    .submit-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        border-top: 1px solid var(--border);
        padding-top: 1rem;
    }

    .submit-hint {
        font-size: .8125rem;
        color: var(--ink-3);
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: linear-gradient(135deg, var(--accent), #7c3aed);
        color: #fff;
        font-size: .9375rem;
        font-weight: 700;
        padding: .6875rem 1.75rem;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        box-shadow: 0 4px 14px rgb(99 102 241/.4);
        transition: opacity .2s, transform .15s, box-shadow .2s;
        white-space: nowrap;
    }

    .btn-primary:hover {
        opacity: .92;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgb(99 102 241/.5);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-primary svg {
        width: 18px;
        height: 18px;
    }

    .btn-primary.loading {
        pointer-events: none;
        opacity: .7;
    }

    /* ── Error banner ── */
    .error-banner {
        background: var(--red-soft);
        border: 1px solid #fca5a5;
        border-radius: var(--radius-sm);
        padding: .875rem 1rem;
        display: flex;
        gap: .75rem;
        align-items: flex-start;
    }

    .error-banner svg {
        width: 18px;
        height: 18px;
        color: var(--red);
        flex-shrink: 0;
        margin-top: 1px;
    }

    .error-banner ul {
        list-style: disc;
        padding-left: 1.25rem;
        font-size: .875rem;
        color: #991b1b;
        line-height: 1.7;
    }

    /* ── Results ── */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .result-section {
        animation: fadeUp .35s ease both;
    }

    .result-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--s1);
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .result-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--surface-2);
    }

    .result-title-group h2 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--ink);
    }

    .result-title-group p {
        margin-top: .2rem;
        font-size: .8125rem;
        color: var(--ink-3);
    }

    .result-count {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        background: var(--accent-soft);
        color: var(--accent-text);
        font-size: .8125rem;
        font-weight: 700;
        padding: .375rem .875rem;
        border-radius: 9999px;
    }

    .segment-wrap {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .segment {
        display: flex;
        background: #e2e8f0;
        padding: 3px;
        border-radius: 8px;
        gap: 2px;
    }

    .segment button {
        border: none;
        background: transparent;
        color: var(--ink-3);
        font-size: .8125rem;
        font-weight: 600;
        padding: .4rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: background .18s, color .18s, box-shadow .18s;
        display: flex;
        align-items: center;
        gap: .375rem;
    }

    .segment button svg {
        width: 14px;
        height: 14px;
    }

    .segment button.active {
        background: var(--surface);
        color: var(--ink);
        box-shadow: var(--s1);
    }

    .result-body {
        padding: 1.5rem;
    }

    .display-view.hidden {
        display: none;
    }

    /* ── Cards ── */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1rem;
    }

    @keyframes cardIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .order-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: var(--surface);
        box-shadow: var(--s0);
        overflow: hidden;
        transition: box-shadow .2s, transform .2s;
        animation: cardIn .3s ease both;
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--s3);
    }

    .order-card-accent {
        height: 4px;
        background: linear-gradient(90deg, var(--accent), #7c3aed);
    }

    .order-card-inner {
        padding: 1.125rem;
    }

    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .5rem;
    }

    .order-title {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--ink);
    }

    .count-pill {
        background: var(--green-soft);
        color: var(--green-text);
        border-radius: 9999px;
        padding: .2rem .625rem;
        font-size: .6875rem;
        font-weight: 700;
        white-space: nowrap;
        letter-spacing: .02em;
        text-transform: uppercase;
    }

    .order-divider {
        height: 1px;
        background: var(--border);
        margin: .875rem 0;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: .375rem;
        font-size: .6875rem;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--ink-3);
        font-weight: 700;
        margin-bottom: .5rem;
    }

    .section-label svg {
        width: 12px;
        height: 12px;
    }

    .ship {
        list-style: none;
    }

    .ship li {
        font-size: .875rem;
        color: var(--ink-2);
        line-height: 1.6;
        padding-left: .875rem;
        position: relative;
    }

    .ship li::before {
        content: '';
        position: absolute;
        left: 0;
        top: .65em;
        width: 4px;
        height: 4px;
        background: var(--ink-3);
        border-radius: 50%;
    }

    .items-list {
        display: flex;
        flex-direction: column;
        gap: .625rem;
    }

    .item {
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        background: var(--surface-2);
        padding: .75rem;
    }

    .item-title {
        font-size: .875rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--ink);
        margin-bottom: .5rem;
    }

    .item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .375rem .75rem;
    }

    .meta-tag {
        font-size: .75rem;
        color: var(--ink-3);
    }

    .meta-tag strong {
        color: var(--ink-2);
        font-weight: 500;
    }

    .item-perso {
        margin-top: .5rem;
        padding: .5rem .625rem;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 6px;
        font-size: .8125rem;
        color: #92400e;
        line-height: 1.5;
    }

    .item-perso-label {
        font-size: .6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #b45309;
        margin-bottom: .25rem;
    }

    .card-error {
        display: flex;
        align-items: flex-start;
        gap: .625rem;
        background: var(--red-soft);
        border: 1px solid #fca5a5;
        border-radius: var(--radius-sm);
        padding: .75rem;
        margin-top: .75rem;
        font-size: .8125rem;
        color: #991b1b;
    }

    .card-error svg {
        width: 16px;
        height: 16px;
        color: var(--red);
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Table ── */
    .table-wrap {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 860px;
        border-collapse: collapse;
        font-size: .875rem;
    }

    thead tr {
        background: var(--surface-2);
        border-bottom: 2px solid var(--border);
    }

    thead th {
        padding: .75rem 1rem;
        text-align: left;
        font-size: .6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--ink-3);
        white-space: nowrap;
    }

    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }

    tbody tr:last-child {
        border-bottom: 0;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    td {
        padding: .875rem 1rem;
        color: var(--ink-2);
        vertical-align: top;
        line-height: 1.55;
    }

    td:first-child {
        font-weight: 600;
        color: var(--ink);
    }

    /* ── Sheet export ── */
    .sheet-export {
        margin-top: 1.25rem;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
    }

    .sheet-export-header {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .75rem;
    }

    .sheet-export-header svg {
        width: 16px;
        height: 16px;
        color: var(--green);
    }

    .sheet-export-header span {
        font-size: .8125rem;
        font-weight: 700;
        color: var(--ink);
    }

    .sheet-export-header small {
        font-size: .75rem;
        color: var(--ink-3);
    }

    .sheet-export textarea {
        width: 100%;
        min-height: 140px;
        font-family: 'SF Mono', Menlo, Consolas, 'Courier New', monospace;
        font-size: .8125rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .75rem;
        color: var(--ink-2);
        resize: vertical;
        transition: border-color .2s;
        line-height: 1.6;
    }

    .sheet-export textarea:focus {
        outline: none;
        border-color: var(--border-focus);
        box-shadow: 0 0 0 3px rgb(99 102 241/.12);
    }

    .sheet-actions {
        margin-top: .75rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .btn-copy {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--ink);
        font-size: .875rem;
        font-weight: 600;
        padding: .5rem 1.125rem;
        cursor: pointer;
        transition: background .15s, border-color .15s;
    }

    .btn-copy:hover {
        background: var(--accent-soft);
        border-color: var(--accent);
        color: var(--accent-text);
    }

    .btn-copy svg {
        width: 15px;
        height: 15px;
    }

    .copy-status {
        font-size: .8125rem;
        font-weight: 500;
    }

    .copy-status.ok {
        color: var(--green);
    }

    .copy-status.err {
        color: var(--red);
    }

    /* ── Empty state ── */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--accent-soft), #e0e7ff);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 30px;
        height: 30px;
        color: var(--accent);
    }

    .empty-state h3 {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--ink);
    }

    .empty-state p {
        font-size: .875rem;
        color: var(--ink-3);
        max-width: 340px;
        line-height: 1.6;
    }

    /* ── Loading overlay ── */
    #loading-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 200;
        background: rgb(15 23 42/.45);
        backdrop-filter: blur(3px);
        align-items: center;
        justify-content: center;
    }

    #loading-overlay.active {
        display: flex;
    }

    .loading-box {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: 2.5rem 3rem;
        text-align: center;
        box-shadow: var(--s3);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
    }

    .spinner {
        width: 48px;
        height: 48px;
        border: 3px solid var(--accent-soft);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-box strong {
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
    }

    .loading-box span {
        font-size: .875rem;
        color: var(--ink-3);
    }

    /* ── Toast ── */
    #toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 300;
        display: flex;
        align-items: center;
        gap: .625rem;
        background: var(--ink);
        color: #fff;
        font-size: .875rem;
        font-weight: 500;
        padding: .75rem 1.25rem;
        border-radius: var(--radius-sm);
        box-shadow: var(--s3);
        opacity: 0;
        transform: translateY(8px);
        transition: opacity .25s, transform .25s;
        pointer-events: none;
    }

    #toast.show {
        opacity: 1;
        transform: translateY(0);
    }

    #toast svg {
        width: 16px;
        height: 16px;
    }

    #toast.ok {
        background: #064e3b;
    }

    #toast.err {
        background: #7f1d1d;
    }

    /* ── Shared utility ── */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        color: var(--accent-text);
        font-size: .875rem;
        font-weight: 600;
        text-decoration: none;
        padding: .5rem .875rem;
        border-radius: var(--radius-sm);
        transition: background .15s;
    }

    .back-link:hover { background: var(--accent-soft); }
    .back-link svg { width: 15px; height: 15px; }

    /* ── History page ── */
    .history-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .history-header h1 {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--ink);
    }

    .history-header p {
        font-size: .875rem;
        color: var(--ink-3);
        margin-top: .2rem;
    }

    .history-stats {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 9999px;
        padding: .375rem .875rem;
        font-size: .8125rem;
        font-weight: 600;
        color: var(--ink-2);
        box-shadow: var(--s0);
    }

    .stat-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot-green  { background: var(--green); }
    .dot-red    { background: var(--red); }
    .dot-accent { background: var(--accent); }

    .history-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--s1);
        overflow: hidden;
    }

    .history-table-wrap { overflow-x: auto; }

    .history-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
        font-size: .875rem;
    }

    .history-table thead tr {
        background: var(--surface-2);
        border-bottom: 2px solid var(--border);
    }

    .history-table thead th {
        padding: .75rem 1rem;
        text-align: left;
        font-size: .6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--ink-3);
        white-space: nowrap;
    }

    .history-table tbody tr.data-row {
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }

    .history-table tbody tr.data-row:hover { background: #f8fafc; }

    .history-table td {
        padding: .75rem 1rem;
        color: var(--ink-2);
        vertical-align: middle;
        line-height: 1.5;
    }

    .history-table td:first-child { color: var(--ink-3); font-size: .8125rem; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .75rem;
        font-weight: 700;
        padding: .2rem .625rem;
        border-radius: 9999px;
    }

    .status-badge.ok  { background: var(--green-soft); color: var(--green-text); }
    .status-badge.err { background: var(--red-soft);   color: #991b1b; }

    .order-number-cell { font-weight: 700; color: var(--ink); }

    .ship-to-cell {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-name-cell {
        font-size: .8125rem;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .items-expand-btn {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: var(--accent-soft);
        color: var(--accent-text);
        border: none;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 600;
        padding: .2rem .625rem;
        cursor: pointer;
        transition: background .15s;
    }

    .items-expand-btn:hover { background: #ddd6fe; }

    .items-expand-btn svg {
        transition: transform .2s;
    }

    /* ── Expandable detail row ── */
    .items-detail-row { display: none; }
    .items-detail-row.open { display: table-row; }

    .items-detail-row.open > td {
        background: #f1f5f9;
        border-top: 2px solid var(--accent);
        border-bottom: 2px solid var(--border);
        padding: .75rem 1rem 1rem !important;
    }

    .items-detail-cell {
        padding: 0 !important;
    }

    .items-mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .8125rem;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .items-mini-table th {
        padding: .4rem .75rem;
        background: #e2e8f0;
        text-align: left;
        font-size: .6875rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ink-3);
        font-weight: 700;
    }

    .items-mini-table td {
        padding: .4rem .75rem;
        border-top: 1px solid var(--border);
        color: var(--ink-2);
        vertical-align: top;
        background: var(--surface);
    }

    .items-mini-table td:first-child { color: var(--ink); font-weight: 500; }

    /* ── Pagination ── */
    .pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        font-size: .8125rem;
        color: var(--ink-3);
    }

    .pagination-wrap .pagination { display: flex; gap: .25rem; list-style: none; }

    .pagination-wrap .pagination li a,
    .pagination-wrap .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 .5rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        color: var(--ink-2);
        text-decoration: none;
        font-size: .8125rem;
        font-weight: 500;
        transition: background .15s, border-color .15s;
    }

    .pagination-wrap .pagination li a:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent-text); }
    .pagination-wrap .pagination li.active span { background: var(--accent); border-color: var(--accent); color: #fff; font-weight: 700; }
    .pagination-wrap .pagination li.disabled span { opacity: .4; cursor: default; }

    /* ── Empty history state ── */
    .empty-history {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 5rem 2rem;
        text-align: center;
    }

    .empty-history svg { color: var(--ink-3); opacity: .4; }
    .empty-history h3 { font-size: 1.0625rem; font-weight: 700; color: var(--ink); }
    .empty-history p { font-size: .875rem; color: var(--ink-3); max-width: 320px; line-height: 1.6; }

    /* ── Support page ── */
    .support-page {
        min-height: calc(100vh - 60px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem 4rem;
    }

    .support-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--s2);
        max-width: 480px;
        width: 100%;
        overflow: hidden;
        text-align: center;
    }

    .support-card-top {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        padding: 2.25rem 2rem 1.75rem;
    }

    .support-heart-icon {
        width: 56px;
        height: 56px;
        background: rgb(255 255 255 / .18);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .support-heart-icon svg {
        width: 28px;
        height: 28px;
        color: #fff;
    }

    .support-card-top h1 {
        font-size: 1.375rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.015em;
    }

    .support-card-top p {
        margin-top: .5rem;
        font-size: .9375rem;
        color: rgb(255 255 255 / .8);
        line-height: 1.6;
    }

    .support-card-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.75rem;
    }

    .bank-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
        width: 100%;
    }

    .qr-wrap {
        width: 220px;
        height: 220px;
        border-radius: var(--radius);
        border: 2px solid var(--border);
        overflow: hidden;
        flex-shrink: 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .bank-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .625rem;
        width: 100%;
    }

    .bank-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: #fff8f0;
        border: 1.5px solid #fed7aa;
        border-radius: 10px;
        padding: .375rem .875rem;
        font-size: .8125rem;
        font-weight: 700;
        color: #c2410c;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .bank-account-row {
        display: flex;
        align-items: center;
        gap: .5rem;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .75rem 1rem;
        width: 100%;
        max-width: 320px;
    }

    .bank-account-row .acct-number {
        flex: 1;
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: .06em;
        font-variant-numeric: tabular-nums;
    }

    .btn-copy-acct {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--ink-3);
        font-size: .8125rem;
        font-weight: 600;
        padding: .375rem .75rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        flex-shrink: 0;
        transition: background .15s, border-color .15s, color .15s;
    }

    .btn-copy-acct:hover {
        background: var(--accent-soft);
        border-color: var(--accent);
        color: var(--accent-text);
    }

    .btn-copy-acct svg { width: 14px; height: 14px; }

    .bank-name-row {
        font-size: .875rem;
        color: var(--ink-3);
    }

    .bank-name-row strong {
        color: var(--ink-2);
        font-weight: 600;
    }

    .support-divider {
        width: 100%;
        height: 1px;
        background: var(--border);
    }

    .thank-you {
        font-size: .9375rem;
        color: var(--ink-2);
        line-height: 1.7;
        max-width: 360px;
    }

    .thank-you strong { color: var(--ink); }

    .copy-inline-status {
        font-size: .75rem;
        font-weight: 600;
        min-height: 1rem;
        color: var(--green);
        transition: opacity .2s;
    }
</style>
