@extends('layouts.main')

@section('title', 'Ủng hộ tác giả – PDF Convert Services')

@section('content')
<div class="support-page">
    <div class="support-card">

        {{-- Card top gradient --}}
        <div class="support-card-top">
            <div class="support-heart-icon">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <h1>Ủng hộ tác giả</h1>
            <p>Công cụ được phát triển miễn phí.<br>Mọi sự ủng hộ đều rất có ý nghĩa! 🙏</p>
        </div>

        {{-- Card body --}}
        <div class="support-card-body">

            <div class="bank-block">
                {{-- QR Code --}}
                <div class="qr-wrap">
                    {{-- QR được generate bởi TpBank VietQR --}}
                    <img
                        src="https://img.vietqr.io/image/TPB-04282025201-compact2.png?amount=&addInfo=ung%20ho%20tool&accountName=LE%20DAI%20AN"
                        alt="QR chuyển khoản TPBank – LE DAI AN"
                        loading="lazy"
                        onerror="this.style.display='none';document.getElementById('qr-fallback').style.display='flex'"
                    >
                    <div id="qr-fallback" style="display:none;flex-direction:column;align-items:center;gap:.5rem;padding:1rem;color:var(--ink-3);font-size:.8125rem">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h.01M14 17h3M17 14v3M20 20h.01"/></svg>
                        QR không tải được.<br>Vui lòng chuyển khoản thủ công.
                    </div>
                </div>

                <div class="bank-info">
                    {{-- Bank badge --}}
                    <div class="bank-badge">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                        TPBank
                    </div>

                    {{-- Account number + copy --}}
                    <div class="bank-account-row">
                        <span class="acct-number" id="acct-number">0428 2025 201</span>
                        <button type="button" class="btn-copy-acct" id="copy-acct-btn" title="Copy số tài khoản">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/></svg>
                            Copy
                        </button>
                    </div>

                    <div class="copy-inline-status" id="copy-inline-status"></div>

                    <div class="bank-name-row">Chủ tài khoản: <strong>LÊ ĐẠI AN</strong></div>
                </div>
            </div>

            <div class="support-divider"></div>

            {{-- Thank you message --}}
            <p class="thank-you">
                Cảm ơn bạn rất nhiều vì đã sử dụng công cụ này! ❤️<br><br>
                Mỗi đồng ủng hộ dù nhỏ đều là động lực để mình tiếp tục
                cải thiện và phát triển thêm nhiều tính năng hữu ích hơn.<br><br>
                <strong>Chúc bạn một ngày tốt lành!</strong>
            </p>

            {{-- Back link --}}
            <a href="{{ route('app') }}" class="back-link">
                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                Về trang chính
            </a>

        </div>
    </div>
</div>
@endsection