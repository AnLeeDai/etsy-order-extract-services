<script>
    (function() {
        // ── DOM refs ──────────────────────────────────────────────────────────────
        var dropZone = document.getElementById('drop-zone');
        var fileInput = document.getElementById('pdf-input');
        var fileList = document.getElementById('file-list');
        var fileChips = document.getElementById('file-chips');
        var countLabel = document.getElementById('file-count-label');
        var dzCompactLabel = document.getElementById('dz-compact-label');
        var submitHint = document.getElementById('submit-hint');
        var form = document.getElementById('upload-form');
        var submitBtn = document.getElementById('submit-btn');
        var overlay = document.getElementById('loading-overlay');
        var loadingPct        = document.getElementById('loading-pct');
        var loadingBar        = document.getElementById('loading-bar');
        var loadingFilenameEl = document.getElementById('loading-filename');
        var toast = document.getElementById('toast');
        var toastMsg = document.getElementById('toast-msg');

        // ── Custom file list (persists across multiple picker opens) ──────────────
        var selectedFiles = [];

        /** Sync selectedFiles array → fileInput.files via DataTransfer */
        function syncInput() {
            try {
                var dt = new DataTransfer();
                selectedFiles.forEach(function(f) {
                    dt.items.add(f);
                });
                fileInput.files = dt.files;
            } catch (x) {
                // DataTransfer not supported (very old browsers) — silently ignore
            }
        }

        /** Merge new File objects into selectedFiles, skip exact duplicates (name+size) */
        function mergeFiles(incoming) {
            Array.from(incoming).forEach(function(f) {
                var dup = selectedFiles.some(function(e) {
                    return e.name === f.name && e.size === f.size;
                });
                if (!dup) selectedFiles.push(f);
            });
        }

        /** Remove file at index i */
        function removeFile(i) {
            selectedFiles.splice(i, 1);
            syncInput();
            render();
        }

        // ── Helpers ───────────────────────────────────────────────────────────────
        function fmtSize(b) {
            return b < 1024 ? b + ' B' :
                b < 1048576 ? (b / 1024).toFixed(1) + ' KB' :
                (b / 1048576).toFixed(1) + ' MB';
        }

        function showToast(msg, type) {
            if (!toast || !toastMsg) return;
            toastMsg.textContent = msg;
            toast.className = 'show ' + (type || '');
            clearTimeout(toast._t);
            toast._t = setTimeout(function() {
                toast.className = '';
            }, 3000);
        }

        // ── Renderer ──────────────────────────────────────────────────────────────
        function render() {
            var hasFiles = selectedFiles.length > 0;

            if (dropZone) dropZone.classList.toggle('has-files', hasFiles);

            if (!hasFiles) {
                fileList.classList.remove('visible');
                submitHint.textContent = 'Chưa có file nào';
                return;
            }

            fileList.classList.add('visible');
            countLabel.textContent = 'Đã chọn ' + selectedFiles.length + ' file';
            if (dzCompactLabel) dzCompactLabel.textContent = selectedFiles.length + ' file đã chọn';
            submitHint.textContent = selectedFiles.length + ' file sẵn sàng';

            fileChips.innerHTML = '';
            selectedFiles.forEach(function(f, i) {
                var c = document.createElement('div');
                c.className = 'file-chip';
                c.innerHTML =
                    '<div class="file-chip-icon">' +
                    '<svg viewBox="0 0 20 20" fill="currentColor">' +
                    '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>' +
                    '</svg>' +
                    '</div>' +
                    '<span class="file-chip-name">' + f.name + '</span>' +
                    '<span class="file-chip-size">' + fmtSize(f.size) + '</span>' +
                    '<button type="button" class="file-chip-remove" aria-label="Xóa ' + f.name +
                    '" data-idx="' + i + '">' +
                    '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>' +
                    '</button>';
                fileChips.appendChild(c);
            });

            // Attach remove handlers
            fileChips.querySelectorAll('.file-chip-remove').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    removeFile(parseInt(btn.getAttribute('data-idx'), 10));
                });
            });
        }

        // ── Drop zone ─────────────────────────────────────────────────────────────
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                mergeFiles(this.files);
                syncInput();
                render();
            });
        }

        if (dropZone) {
            // Click anywhere on zone → open file picker
            dropZone.addEventListener('click', function(e) {
                if (e.target === fileInput) return;
                fileInput.click();
            });

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', function() {
                dropZone.classList.remove('drag-over');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                if (e.dataTransfer && e.dataTransfer.files) {
                    mergeFiles(e.dataTransfer.files);
                    syncInput();
                    render();
                }
            });
        }

        // ── Progress overlay helpers ───────────────────────────────────────────────
        function updateOverlay(done, total, filename) {
            var pct = total > 0 ? Math.round((done / total) * 100) : 0;
            if (loadingPct) loadingPct.textContent = done + ' / ' + total + ' file';
            if (loadingBar) loadingBar.style.width = pct + '%';
            if (loadingFilenameEl) loadingFilenameEl.textContent = filename || '';
        }

        function escHtml(s) {
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        async function processSingleFile(file) {
            var fd = new FormData();
            fd.append('pdf_file', file);
            fd.append('_token', document.querySelector('input[name="_token"]').value);
            var resp = await fetch('{{ route("pdf.extract.single") }}', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!resp.ok) {
                var errMsg = 'HTTP ' + resp.status;
                try { var j = await resp.json(); errMsg = j.message || j.error || errMsg; } catch(_) {}
                throw new Error(errMsg);
            }
            return await resp.json();
        }

        function buildResultsSection(allSheetRows, totalFiles, errorCount) {
            var headers = ['order_number', 'ship_to', 'sku', 'title', 'item_count', 'personalization', 'size'];
            var filtered = allSheetRows.filter(function(r) { return r.some(function(c) { return c !== ''; }); });
            var tsvLines = [];
            filtered.forEach(function(r) { tsvLines.push(r.join('\t')); });
            var tsvText = tsvLines.join('\n');

            var hCells = headers.map(function(h) {
                return '<th>' + h.replace(/_/g, ' ').toUpperCase() + '</th>';
            }).join('');

            var bRows = filtered.length === 0
                ? '<tr><td colspan="' + headers.length + '" style="text-align:center;padding:2.5rem;color:var(--ink-3)">Không có dữ liệu.</td></tr>'
                : filtered.map(function(row) {
                    return '<tr>' + row.map(function(c) { return '<td>' + escHtml(c) + '</td>'; }).join('') + '</tr>';
                  }).join('');

            var svgCopy = '<svg viewBox="0 0 20 20" fill="currentColor"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>';
            var svgReset = '<svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>';

            return '<div class="result-section" id="result-panel">' +
                '<div class="result-card">' +
                    '<div class="result-header">' +
                        '<div class="result-title-group"><h2>Kết quả trích xuất</h2><p>' + totalFiles + ' file' + (errorCount > 0 ? ' &middot; <span style="color:var(--red);font-weight:700">' + errorCount + ' lỗi</span>' : '') + '</p></div>' +
                        '<div style="display:flex;align-items:center;gap:.75rem">' +
                            '<textarea id="sheet-export-text" style="display:none" readonly spellcheck="false">' + escHtml(tsvText) + '</textarea>' +
                            '<button type="button" id="copy-sheet-button" class="btn-copy">' + svgCopy + 'Copy sang Google Sheets</button>' +
                            '<span id="copy-sheet-status" class="copy-status" aria-live="polite"></span>' +
                            '<button type="button" id="reset-btn" class="btn-reset">' + svgReset + 'Bắt đầu lại</button>' +
                        '</div>' +
                    '</div>' +
                    '<div class="result-body">' +
                        '<div class="table-wrap"><table><thead><tr>' + hCells + '</tr></thead><tbody>' + bRows + '</tbody></table></div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }

        function attachResetHandler() {
            var btn = document.getElementById('reset-btn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                var ajaxSection = document.getElementById('ajax-result-section');
                if (ajaxSection) ajaxSection.innerHTML = '';
                var emptyPanel = document.getElementById('empty-state-panel');
                if (emptyPanel) emptyPanel.style.display = '';
                selectedFiles = [];
                syncInput();
                render();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        function attachCopyHandler() {
            var btn = document.getElementById('copy-sheet-button');
            var sta = document.getElementById('copy-sheet-status');
            var txt = document.getElementById('sheet-export-text');
            if (!btn || !txt) return;
            btn.addEventListener('click', async function() {
                var text = txt.value || '';
                if (!text) { showToast('Không có dữ liệu để copy.', 'err'); return; }
                var ok = false;
                try {
                    await navigator.clipboard.writeText(text);
                    ok = true;
                } catch(_) {
                    // Fallback: detached textarea to avoid page styles bleeding into clipboard HTML
                    var tmp = document.createElement('textarea');
                    tmp.value = text;
                    tmp.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0;font-family:monospace;color:#000;background:#fff;border:none';
                    document.body.appendChild(tmp);
                    tmp.focus();
                    tmp.select();
                    try { ok = document.execCommand('copy'); } catch(x) {}
                    document.body.removeChild(tmp);
                }
                if (sta) {
                    sta.textContent = ok ? ' Đã copy!' : ' Thất bại';
                    sta.className = 'copy-status ' + (ok ? 'ok' : 'err');
                    setTimeout(function() { sta.textContent = ''; sta.className = 'copy-status'; }, 3000);
                }
                showToast(ok ? 'Đã copy! Paste vào Google Sheets bằng Ctrl+V.' : 'Copy thất bại, hãy thử thủ công.', ok ? 'ok' : 'err');
            });
        }

        // ── Form submit → sequential AJAX processing ──────────────────────────────
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!selectedFiles.length) return;

                var files = selectedFiles.slice();
                var total = files.length;
                var allRows = [];
                var errorCount = 0;

                submitBtn.disabled = true;
                submitBtn.classList.add('loading');
                if (overlay) overlay.classList.add('active');
                updateOverlay(0, total, files[0] ? files[0].name : '');

                var chips = fileChips ? Array.from(fileChips.querySelectorAll('.file-chip')) : [];

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    updateOverlay(i, total, file.name);
                    if (chips[i]) chips[i].classList.add('chip-processing');

                    try {
                        var data = await processSingleFile(file);
                        if (data.sheet_rows && Array.isArray(data.sheet_rows)) {
                            data.sheet_rows.forEach(function(r) {
                                if (r.some(function(c) { return c !== ''; })) allRows.push(r);
                            });
                        }
                        if (chips[i]) { chips[i].classList.remove('chip-processing'); chips[i].classList.add('chip-done'); }
                    } catch(err) {
                        errorCount++;
                        if (chips[i]) { chips[i].classList.remove('chip-processing'); chips[i].classList.add('chip-error'); }
                        showToast('Lỗi: ' + file.name, 'err');
                    }
                }

                updateOverlay(total, total, '');
                await new Promise(function(r) { setTimeout(r, 350); });

                if (overlay) overlay.classList.remove('active');
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');

                var ajaxSection = document.getElementById('ajax-result-section');
                if (ajaxSection) {
                    var emptyPanel = document.getElementById('empty-state-panel');
                    if (emptyPanel) emptyPanel.style.display = 'none';
                    ajaxSection.innerHTML = buildResultsSection(allRows, total, errorCount);
                    attachCopyHandler();
                    attachResetHandler();
                    setTimeout(function() {
                        ajaxSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }

                showToast(
                    errorCount === 0
                        ? 'Xử lý xong ' + total + ' file!'
                        : 'Xong! ' + (total - errorCount) + '/' + total + ' file thành công.',
                    errorCount === 0 ? 'ok' : 'err'
                );
            });
        }

        // ── Copy to Google Sheets ─────────────────────────────────────────────────
        var copyBtn = document.getElementById('copy-sheet-button');
        var copyStatus = document.getElementById('copy-sheet-status');
        var exportText = document.getElementById('sheet-export-text');

        if (copyBtn && exportText) {
            copyBtn.addEventListener('click', async function() {
                var text = exportText.value || '';
                if (!text) {
                    showToast('Không có dữ liệu để copy.', 'err');
                    return;
                }

                var ok = false;
                try {
                    await navigator.clipboard.writeText(text);
                    ok = true;
                } catch (_) {
                    var tmp = document.createElement('textarea');
                    tmp.value = text;
                    tmp.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0;font-family:monospace;color:#000;background:#fff;border:none';
                    document.body.appendChild(tmp);
                    tmp.focus();
                    tmp.select();
                    try { ok = document.execCommand('copy'); } catch (x) {}
                    document.body.removeChild(tmp);
                }

                if (copyStatus) {
                    copyStatus.textContent = ok ? ' Đã copy!' : ' Thất bại';
                    copyStatus.className = 'copy-status ' + (ok ? 'ok' : 'err');
                    setTimeout(function() {
                        copyStatus.textContent = '';
                        copyStatus.className = 'copy-status';
                    }, 3000);
                }

                showToast(
                    ok ? 'Đã copy! Paste vào Google Sheets bằng Ctrl+V.' :
                    'Copy thất bại, hãy thử thủ công.',
                    ok ? 'ok' : 'err'
                );
            });
        }

        /* ── Support: copy account number ── */
        var copyAcctBtn  = document.getElementById('copy-acct-btn');
        var copyInlineSt = document.getElementById('copy-inline-status');
        if (copyAcctBtn) {
            var acctRaw = '04282025201';
            copyAcctBtn.addEventListener('click', async function () {
                var ok = false;
                try { await navigator.clipboard.writeText(acctRaw); ok = true; }
                catch (_) {
                    var ta = document.createElement('textarea');
                    ta.value = acctRaw; document.body.appendChild(ta);
                    ta.select(); try { ok = document.execCommand('copy'); } catch (x) {}
                    document.body.removeChild(ta);
                }
                if (copyInlineSt) {
                    copyInlineSt.textContent = ok ? '✓ Đã copy số tài khoản!' : '✗ Copy thất bại';
                    copyInlineSt.style.color  = ok ? 'var(--green)' : 'var(--red)';
                    setTimeout(function () { copyInlineSt.textContent = ''; }, 3000);
                }
                showToast(
                    ok ? 'Đã copy số TK: 04282025201' : 'Copy thất bại, hãy copy thủ công.',
                    ok ? 'ok' : 'err'
                );
            });
        }
    })();
</script>
