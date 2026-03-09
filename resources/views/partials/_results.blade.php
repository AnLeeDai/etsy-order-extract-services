@isset($results)
    <div class="result-section" id="result-panel">
        <div class="result-card">
            {{-- Header --}}
            <div class="result-header">
                <div class="result-title-group">
                    <h2>Kết quả trích xuất</h2>
                    <p>{{ count($results) }} file</p>
                </div>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <textarea id="sheet-export-text" style="display:none" readonly spellcheck="false">{{ $sheetText }}</textarea>
                    <button type="button" id="copy-sheet-button" class="btn-copy">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                        </svg>
                        Copy sang Google Sheets
                    </button>
                    <span id="copy-sheet-status" class="copy-status" aria-live="polite"></span>
                </div>
            </div>

            <div class="result-body">
                {{-- Table view --}}
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                @foreach ($sheetHeaders as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sheetRows as $sheetRow)
                                <tr>
                                    @foreach ($sheetRow as $cell)
                                        <td>{!! nl2br(e($cell)) !!}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(count($sheetHeaders), 1) }}"
                                        style="text-align:center;padding:2.5rem;color:var(--ink-3)">Không có dữ liệu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Empty state: no results yet --}}
    <div id="empty-state-panel" class="result-card"
        style="border:1px solid var(--border);border-radius:var(--radius-lg);background:var(--surface)">
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                </svg>
            </div>
            <h3>Chưa có kết quả</h3>
            <p>Upload file PDF Etsy Order ở trên để bắt đầu trích xuất dữ liệu đơn hàng.</p>
        </div>
    </div>
@endisset
