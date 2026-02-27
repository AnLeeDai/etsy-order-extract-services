@isset($results)
    <div class="result-section" id="result-panel">
        <div class="result-card">
            {{-- Header --}}
            <div class="result-header">
                <div class="result-title-group">
                    <h2>Kết quả trích xuất</h2>
                    <p>Copy sang Google Sheets bằng nút bên dưới</p>
                </div>
                <div class="result-count">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width:14px;height:14px">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ count($results) }} file
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
                                        <td>{{ $cell }}</td>
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

                {{-- Google Sheets export --}}
                <div class="sheet-export">
                    <div class="sheet-export-header">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Copy vào Google Sheets</span>
                        <small>&middot; tab-delimited, paste trực tiếp</small>
                    </div>
                    <textarea id="sheet-export-text" readonly spellcheck="false">{{ $sheetText }}</textarea>
                    <div class="sheet-actions">
                        <button type="button" id="copy-sheet-button" class="btn-copy">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                <path
                                    d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                            </svg>
                            Copy dữ liệu
                        </button>
                        <span id="copy-sheet-status" class="copy-status" aria-live="polite"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Empty state: no results yet --}}
    <div class="result-card"
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
