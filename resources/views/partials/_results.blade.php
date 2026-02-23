@isset($results)
    <div class="result-section" id="result-panel">
        <div class="result-card">
            {{-- Header with view toggle --}}
            <div class="result-header">
                <div class="result-title-group">
                    <h2>Kết quả trích xuất</h2>
                    <p>Card để xem nhanh &middot; Table để copy sang Google Sheets</p>
                </div>
                <div class="segment-wrap">
                    <div class="result-count">
                        <svg viewBox="0 0 20 20" fill="currentColor" style="width:14px;height:14px">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ count($results) }} file
                    </div>
                    <div class="segment">
                        <button type="button" data-view-mode="card" class="active">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Card
                        </button>
                        <button type="button" data-view-mode="table">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd" />
                            </svg>
                            Table
                        </button>
                    </div>
                </div>
            </div>

            <div class="result-body">
                {{-- Card view --}}
                <div class="display-view" id="card-view">
                    @if (count($results) === 0)
                        <div class="empty-state">
                            <div class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg></div>
                            <h3>Chưa có dữ liệu</h3>
                            <p>Không tìm thấy thông tin đơn hàng trong các file đã tải lên.</p>
                        </div>
                    @else
                        <div class="cards">
                            @foreach ($results as $entry)
                                <article class="order-card">
                                    <div class="order-card-accent"></div>
                                    <div class="order-card-inner">
                                        @if (($entry['success'] ?? false) === false)
                                            <div class="order-title" style="font-size:.9375rem">
                                                {{ $entry['file_name'] ?? 'PDF' }}</div>
                                            <div class="card-error">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ $entry['error'] ?? 'Không thể phân tích file này.' }}</span>
                                            </div>
                                        @else
                                            @php($result = $entry['result'] ?? [])
                                            <div class="order-head">
                                                <div class="order-title">Order #{{ $result['order_number'] ?? '' }}</div>
                                                <span class="count-pill">{{ $result['item_count'] ?? '0' }} items</span>
                                            </div>
                                            <div class="order-divider"></div>
                                            <div class="section-label">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Ship to
                                            </div>
                                            <ul class="ship">
                                                @forelse (($result['ship_to']['lines'] ?? []) as $line)
                                                    <li>{{ $line }}</li>
                                                @empty
                                                    <li style="color:var(--ink-3);padding-left:0">Không tìm thấy địa chỉ
                                                    </li>
                                                @endforelse
                                            </ul>
                                            <div class="order-divider"></div>
                                            <div class="section-label">
                                                <svg viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                                </svg>
                                                Items
                                            </div>
                                            <div class="items-list">
                                                @forelse (($result['items'] ?? []) as $item)
                                                    <div class="item">
                                                        <div class="item-title">{{ $item['title'] ?? '-' }}</div>
                                                        <div class="item-meta">
                                                            <span class="meta-tag">SKU:
                                                                <strong>{{ $item['sku'] ?? '-' }}</strong></span>
                                                            <span class="meta-tag">Size:
                                                                <strong>{{ $item['size'] ?? '-' }}</strong></span>
                                                        </div>
                                                        @if (!empty($item['personalization']) && $item['personalization'] !== '-')
                                                            <div class="item-perso">
                                                                <div class="item-perso-label">Personalization</div>
                                                                {{ $item['personalization'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <div style="font-size:.875rem;color:var(--ink-3)">Không tìm thấy sản
                                                        phẩm.</div>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Table view --}}
                <div class="display-view hidden" id="table-view">
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
