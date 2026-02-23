@extends('layouts.main')

@section('title', 'Etsy Order Extract')

@push('body-before')
{{-- Loading overlay --}}
<div id="loading-overlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <div><strong>Đang xử lý PDF</strong><br><span>Vui lòng đợi trong giây lát</span></div>
    </div>
</div>
@endpush

@section('content')
@php
    $sheetHeaders = $sheetHeaders ?? [];
    $sheetRows    = $sheetRows    ?? [];
    $tsvLines     = [];
    if ($sheetHeaders !== []) {
        $tsvLines[] = implode("\t", $sheetHeaders);
    }
    foreach ($sheetRows as $sheetRow) {
        $tsvLines[] = implode("\t", $sheetRow);
    }
    $sheetText = implode("\n", $tsvLines);
@endphp

<div class="page">
    @include('partials._upload-card')
    @include('partials._results')
</div>
@endsection
