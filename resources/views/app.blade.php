@extends('layouts.main')

@section('title', 'Etsy Order Extract')

@push('body-before')
{{-- Loading overlay --}}
<div id="loading-overlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <div>
            <strong>Đang xử lý PDF</strong>
            <span id="loading-pct">Vui lòng đợi...</span>
        </div>
        <div class="loading-progress-wrap">
            <div class="loading-progress-bar" id="loading-bar"></div>
        </div>
        <div class="loading-filename" id="loading-filename"></div>
    </div>
</div>
@endpush

@section('content')
@php
    $sheetHeaders = $sheetHeaders ?? [];
    $sheetRows    = $sheetRows    ?? [];
    $tsvLines     = [];
    foreach ($sheetRows as $sheetRow) {
        if (array_filter($sheetRow, fn(string $c) => $c !== '') !== []) {
            $tsvLines[] = implode("\t", $sheetRow);
        }
    }
    $sheetText = implode("\n", $tsvLines);
@endphp

<div class="page">
    @include('partials._upload-card')
    <div id="ajax-result-section"></div>
    @include('partials._results')
</div>
@endsection
