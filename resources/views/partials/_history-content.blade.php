{{-- ── History: header + stats + table ── --}}

@php
    $total   = $logs->total();
    $success = $logs->getCollection()->where('success', true)->count();
    $fail    = $logs->getCollection()->where('success', false)->count();

    // Generate page-level TSV for "Copy trang này" button
    $tsvLines = [];
    foreach ($logs as $log) {
        if (! $log->success || empty($log->items)) {
            continue;
        }
        $orderNumber = $log->order_number ?? '';
        $shipToStr   = trim(str_replace(["	", "\r", "\n"], ' ', $log->ship_to ?? ''));
        foreach ($log->items as $item) {
            $sku   = trim(preg_replace('/\s+/', ' ', $item['sku']   ?? ''));
            $title = trim(preg_replace('/\s+/', ' ', $item['title'] ?? ''));
            $perso = trim(preg_replace('/\s+/', ' ', $item['personalization'] ?? ''));
            $size  = trim(preg_replace('/\s+/', ' ', $item['size']  ?? ''));
            if ($sku === '' && $title === '' && $perso === '' && $size === '') {
                continue;
            }
            $tsvLines[] = implode("\t", [
                $orderNumber,
                $shipToStr,
                $sku,
                $title,
                (string) ($item['quantity'] ?? $log->item_count ?? ''),
                $perso,
                $size,
            ]);
        }
    }
    $pageTsv = implode("\n", $tsvLines);
@endphp

<div class="history-header">
    <div>
        <h1>Lịch sử trích xuất</h1>
        <p>{{ number_format($total) }} bản ghi &mdash; trang {{ $logs->currentPage() }}&nbsp;/&nbsp;{{ $logs->lastPage() }}</p>
    </div>
    <div class="history-header-right">
        @if (!empty($pageTsv))
            <textarea id="history-page-tsv" style="display:none" readonly spellcheck="false">{{ $pageTsv }}</textarea>
            <button type="button" id="history-copy-all-btn" class="btn-copy">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                </svg>
                Copy trang này
            </button>
            <span id="history-copy-all-status" class="copy-status" aria-live="polite"></span>
        @endif
        <div class="history-stats">
            <span class="stat-pill"><span class="dot dot-accent"></span>{{ number_format($total) }} tổng</span>
            <span class="stat-pill"><span class="dot dot-green"></span>{{ $success }} thành công</span>
            <span class="stat-pill"><span class="dot dot-red"></span>{{ $fail }} lỗi</span>
        </div>
    </div>
</div>

<div class="history-card">
    @if ($logs->isEmpty())
        <div class="empty-history">
            <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <h3>Chưa có lịch sử</h3>
            <p>Upload và trích xuất PDF ở trang chính để dữ liệu được lưu vào đây.</p>
            <a href="{{ route('app') }}" class="back-link">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                Về trang chính
            </a>
        </div>
    @else
        <div class="history-table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Thời gian</th>
                        <th>File PDF</th>
                        <th>Order</th>
                        <th>Ship To</th>
                        <th>Items</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        @php
                            // Per-row TSV for individual copy button
                            $rowTsvLines = [];
                            if ($log->success && !empty($log->items)) {
                                $rowOrder   = $log->order_number ?? '';
                                $rowShipTo  = trim(str_replace(["	", "\r", "\n"], ' ', $log->ship_to ?? ''));
                                foreach ($log->items as $item) {
                                    $rSku   = trim(preg_replace('/\s+/', ' ', $item['sku']   ?? ''));
                                    $rTitle = trim(preg_replace('/\s+/', ' ', $item['title'] ?? ''));
                                    $rPerso = trim(preg_replace('/\s+/', ' ', $item['personalization'] ?? ''));
                                    $rSize  = trim(preg_replace('/\s+/', ' ', $item['size']  ?? ''));
                                    if ($rSku === '' && $rTitle === '' && $rPerso === '' && $rSize === '') continue;
                                    $rowTsvLines[] = implode("\t", [
                                        $rowOrder,
                                        $rowShipTo,
                                        $rSku,
                                        $rTitle,
                                        (string) ($item['quantity'] ?? $log->item_count ?? ''),
                                        $rPerso,
                                        $rSize,
                                    ]);
                                }
                            }
                            $rowTsv = implode("\n", $rowTsvLines);
                        @endphp

                        {{-- ── Data row ── --}}
                        <tr class="data-row">
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="file-name-cell" title="{{ $log->file_name }}">{{ $log->file_name }}</td>
                            <td class="order-number-cell">
                                {{ $log->order_number ? '#'.$log->order_number : '—' }}
                            </td>
                            <td class="ship-to-cell" title="{{ $log->ship_to }}">
                                {{ $log->ship_to ?: '—' }}
                            </td>
                            <td>
                                @if ($log->success && !empty($log->items))
                                    <button
                                        type="button"
                                        class="items-expand-btn"
                                        data-target="items-{{ $log->id }}"
                                        aria-expanded="false"
                                    >
                                        <svg class="expand-arrow" viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M10 12l-4-4h8l-4 4z"/></svg>
                                        {{ $log->item_count }} item
                                    </button>
                                @else
                                    <span style="color:var(--ink-3)">{{ $log->item_count ?: '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($log->success)
                                    <span class="status-badge ok">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="10" height="10"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        OK
                                    </span>
                                @else
                                    <span class="status-badge err" title="{{ $log->error }}">
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="10" height="10"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        Lỗi
                                    </span>
                                @endif
                            </td>
                            <td class="row-actions-cell">
                                @if (!empty($rowTsv))
                                    <button
                                        type="button"
                                        class="row-copy-btn"
                                        data-tsv="{{ e($rowTsv) }}"
                                        title="Copy order #{{ $log->order_number }} sang Sheets"
                                        aria-label="Copy order #{{ $log->order_number }}"
                                    >
                                        <svg viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                        </svg>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- ── Expandable items detail row ── --}}
                        @if ($log->success && !empty($log->items))
                            <tr class="items-detail-row" id="items-{{ $log->id }}">
                                <td class="items-detail-cell" colspan="8">
                                    <table class="items-mini-table">
                                        <thead>
                                            <tr>
                                                <th>Tên sản phẩm</th>
                                                <th>SKU</th>
                                                <th>Size</th>
                                                <th>Personalization</th>
                                                <th>SL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($log->items as $item)
                                                <tr>
                                                    <td>{{ $item['title'] ?? '—' }}</td>
                                                    <td>{{ $item['sku'] ?? '—' }}</td>
                                                    <td>{{ $item['size'] ?? '—' }}</td>
                                                    <td>{{ $item['personalization'] ?? '—' }}</td>
                                                    <td>{{ $item['quantity'] ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <span>Hiển thị {{ $logs->firstItem() }}–{{ $logs->lastItem() }} / {{ number_format($logs->total()) }} bản ghi</span>
            {{ $logs->links() }}
        </div>
    @endif
</div>

<script>
(function () {
    /* ── Helpers ── */
    function showToast(msg, type) {
        var t = document.getElementById('toast');
        var s = document.getElementById('toast-msg');
        if (!t || !s) return;
        s.textContent = msg;
        t.className = 'show ' + (type || '');
        clearTimeout(t._t);
        t._t = setTimeout(function () { t.className = ''; }, 3000);
    }

    async function copyText(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (_) {
            var tmp = document.createElement('textarea');
            tmp.value = text;
            tmp.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
            document.body.appendChild(tmp);
            tmp.focus();
            tmp.select();
            var ok = false;
            try { ok = document.execCommand('copy'); } catch (x) {}
            document.body.removeChild(tmp);
            return ok;
        }
    }

    /* ── Items expand toggle ── */
    document.querySelectorAll('.items-expand-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var row = document.getElementById(targetId);
            if (!row) return;
            var isOpen = row.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            var arrow = btn.querySelector('.expand-arrow');
            if (arrow) arrow.style.transform = isOpen ? 'rotate(180deg)' : '';
        });
    });

    /* ── Page-level copy (Copy trang này) ── */
    var copyAllBtn = document.getElementById('history-copy-all-btn');
    var copyAllStatus = document.getElementById('history-copy-all-status');
    var pageTsv = document.getElementById('history-page-tsv');
    if (copyAllBtn && pageTsv) {
        copyAllBtn.addEventListener('click', async function () {
            var text = pageTsv.value || '';
            if (!text) { showToast('Không có dữ liệu để copy.', 'err'); return; }
            var ok = await copyText(text);
            if (copyAllStatus) {
                copyAllStatus.textContent = ok ? ' Đã copy!' : ' Thất bại';
                copyAllStatus.className = 'copy-status ' + (ok ? 'ok' : 'err');
                setTimeout(function () { copyAllStatus.textContent = ''; copyAllStatus.className = 'copy-status'; }, 3000);
            }
            showToast(ok ? 'Đã copy trang này! Paste vào Google Sheets bằng Ctrl+V.' : 'Copy thất bại, hãy thử thủ công.', ok ? 'ok' : 'err');
        });
    }

    /* ── Per-row copy ── */
    document.querySelectorAll('.row-copy-btn').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            var text = btn.getAttribute('data-tsv') || '';
            if (!text) return;
            var ok = await copyText(text);
            if (ok) {
                btn.classList.add('row-copy-btn--done');
                setTimeout(function () { btn.classList.remove('row-copy-btn--done'); }, 2000);
            }
            showToast(ok ? 'Đã copy order! Paste vào Sheets bằng Ctrl+V.' : 'Copy thất bại.', ok ? 'ok' : 'err');
        });
    });
})();
</script>
