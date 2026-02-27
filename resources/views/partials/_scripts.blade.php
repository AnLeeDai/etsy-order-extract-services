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

        // ── Form submit → loading overlay ────────────────────────────────────────
        if (form && overlay) {
            form.addEventListener('submit', function() {
                if (fileInput && fileInput.files && fileInput.files.length) {
                    submitBtn.classList.add('loading');
                    overlay.classList.add('active');
                }
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
                    exportText.focus();
                    exportText.select();
                    try {
                        ok = document.execCommand('copy');
                    } catch (x) {}
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
