{{-- ── History: header + stats + table ── --}}

@php
    $total   = $logs->total();
    $success = $logs->getCollection()->where('success', true)->count();
    $fail    = $logs->getCollection()->where('success', false)->count();
@endphp

<div class="history-header">
    <div>
        <h1>Lịch sử trích xuất</h1>
        <p>{{ number_format($total) }} bản ghi &mdash; trang {{ $logs->currentPage() }}&nbsp;/&nbsp;{{ $logs->lastPage() }}</p>
    </div>
    <div class="history-stats">
        <span class="stat-pill"><span class="dot dot-accent"></span>{{ number_format($total) }} tổng</span>
        <span class="stat-pill"><span class="dot dot-green"></span>{{ $success }} thành công</span>
        <span class="stat-pill"><span class="dot dot-red"></span>{{ $fail }} lỗi</span>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
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
                                    >
                                        <svg viewBox="0 0 20 20" fill="currentColor" width="12" height="12"><path d="M10 12l-4-4h8l-4 4z"/></svg>
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
                        </tr>

                        {{-- ── Expandable items detail row ── --}}
                        @if ($log->success && !empty($log->items))
                            <tr class="items-detail-row" id="items-{{ $log->id }}">
                                <td class="items-detail-cell" colspan="7">
                                    <table class="items-mini-table">
                                        <thead>
                                            <tr>
                                                <th>Tên sản phẩm</th>
                                                <th>SKU</th>
                                                <th>Size</th>
                                                <th>Personalization</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($log->items as $item)
                                                <tr>
                                                    <td>{{ $item['title'] ?? '—' }}</td>
                                                    <td>{{ $item['sku'] ?? '—' }}</td>
                                                    <td>{{ $item['size'] ?? '—' }}</td>
                                                    <td>{{ $item['personalization'] ?? '—' }}</td>
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
