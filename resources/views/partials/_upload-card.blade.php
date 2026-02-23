<div class="upload-card">
    <div class="upload-card-header">
        <h2>Tải lên file PDF</h2>
        <p>Hỗ trợ nhiều file cùng lúc. Kéo thả hoặc nhấn để chọn.</p>
    </div>
    <div class="upload-card-body">
        <form id="upload-form" method="POST" action="{{ route('pdf.extract') }}" enctype="multipart/form-data">
            @csrf
            <div class="drop-zone" id="drop-zone">
                <input type="file" name="pdf_files[]" id="pdf-input" accept="application/pdf" multiple required>
                <div class="drop-zone-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 16 12 12 8 16" />
                        <line x1="12" y1="12" x2="12" y2="21" />
                        <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3" />
                    </svg>
                </div>
                <div class="drop-zone-text">Kéo thả PDF vào đây</div>
                <div class="drop-zone-sub">hoặc <strong>nhấn để chọn file</strong> &mdash; chấp nhận nhiều file .pdf
                </div>
                <div class="drop-zone-compact">
                    <div class="drop-zone-compact-icon">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="drop-zone-compact-label" id="dz-compact-label">0 file đã chọn</span>
                    <span class="drop-zone-compact-hint">&middot; nhấn để thay đổi</span>
                </div>
            </div>

            <div id="file-list">
                <div class="file-list-header"><span id="file-count-label">0 file đã chọn</span></div>
                <div class="file-chips" id="file-chips"></div>
            </div>

            <div class="submit-row">
                <span class="submit-hint" id="submit-hint">Chưa có file nào</span>
                <button type="submit" class="btn-primary" id="submit-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 2 11 13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                    Trích xuất dữ liệu
                </button>
            </div>
        </form>

        @if ($errors->any())
            <div class="error-banner">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
