<?php

namespace App\Http\Controllers;

use App\Models\ExtractionLog;
use App\Services\EtsyOrderPdfParser;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use Throwable;

class PDFConvertController extends Controller
{
    public function index()
    {
        $results      = session('pdf_results');
        $sheetHeaders = session('pdf_sheet_headers', []);
        $sheetRows    = session('pdf_sheet_rows', []);

        if ($results !== null) {
            return view('app', compact('results', 'sheetHeaders', 'sheetRows'));
        }

        return view('app');
    }

    public function history()
    {
        $logs = ExtractionLog::latest()->paginate(50);

        return view('history', compact('logs'));
    }

    public function __construct(
        private readonly Parser $parser,
        private readonly EtsyOrderPdfParser $etsyOrderPdfParser,
    ) {
    }

    public function extract(Request $request)
    {
        $validated = $request->validate([
            'pdf_files' => ['required', 'array', 'min:1'],
            'pdf_files.*' => ['required', 'file', 'mimetypes:application/pdf', 'max:20480'],
        ]);

        $results = [];

        foreach ($validated['pdf_files'] as $uploadedFile) {
            $results[] = $this->extractFromUploadedFile($uploadedFile);
        }

        $sheetHeaders = [
            'order_number',
            'ship_to',
            'item_count',
            'title',
            'sku',
            'size',
            'personalization',
        ];
        $sheetRows = $this->buildSheetRows($results);

        // Persist each file result to the database
        foreach ($results as $entry) {
            $result = is_array($entry['result'] ?? null) ? $entry['result'] : [];
            $shipToData = is_array($result['ship_to'] ?? null) ? $result['ship_to'] : [];

            ExtractionLog::create([
                'file_name'    => $entry['file_name'],
                'success'      => $entry['success'] ?? false,
                'error'        => $entry['error'] ?? null,
                'order_number' => $result['order_number'] ?? null,
                'ship_to'      => $shipToData['full'] ?? null,
                'item_count'   => (int) ($result['item_count'] ?? 0),
                'items'        => $result['items'] ?? null,
                'raw_result'   => $result ?: null,
            ]);
        }

        if ($request->wantsJson()) {
            $successCount = count(array_filter($results, fn (array $row): bool => $row['success'] === true));

            return response()->json([
                'total_files' => count($results),
                'success_files' => $successCount,
                'files' => $results,
                'sheet_headers' => $sheetHeaders,
                'sheet_rows' => $sheetRows,
            ]);
        }

        return redirect()->route('app')
            ->with('pdf_results', $results)
            ->with('pdf_sheet_headers', $sheetHeaders)
            ->with('pdf_sheet_rows', $sheetRows);
    }

    /**
     * @return array{
     *     file_name: string,
     *     success: bool,
     *     result: array<string, mixed>|null,
     *     error: string|null
     * }
     */
    private function extractFromUploadedFile(UploadedFile $uploadedFile): array
    {
        $fileName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->getRealPath();

        if ($filePath === false) {
            return [
                'file_name' => $fileName,
                'success' => false,
                'result' => null,
                'error' => 'Khong doc duoc file PDF da upload.',
            ];
        }

        try {
            $text = $this->parser->parseFile($filePath)->getText();
        } catch (Throwable) {
            return [
                'file_name' => $fileName,
                'success' => false,
                'result' => null,
                'error' => 'File PDF khong hop le hoac khong the phan tich.',
            ];
        }

        return [
            'file_name' => $fileName,
            'success' => true,
            'result' => $this->etsyOrderPdfParser->parse($text),
            'error' => null,
        ];
    }

    /**
     * @param array<int, array{
     *     file_name: string,
     *     success: bool,
     *     result: array<string, mixed>|null,
     *     error: string|null
     * }> $results
     * @return array<int, array<int, string>>
     */
    private function buildSheetRows(array $results): array
    {
        $rows = [];

        foreach ($results as $entry) {
            if (($entry['success'] ?? false) !== true) {
                continue;
            }

            /** @var array<string, mixed> $result */
            $result = is_array($entry['result'] ?? null) ? $entry['result'] : [];
            $orderNumber = $this->sanitizeForSheet((string) ($result['order_number'] ?? ''));
            $shipToData = is_array($result['ship_to'] ?? null) ? $result['ship_to'] : [];
            $shipTo = $this->sanitizeForSheet((string) ($shipToData['full'] ?? ''));
            $itemCount = (string) ($result['item_count'] ?? '');
            $items = is_array($result['items'] ?? null) ? $result['items'] : [];

            if ($items === []) {
                $rows[] = [
                    $orderNumber,
                    $shipTo,
                    $itemCount,
                    '',
                    '',
                    '',
                    '',
                ];

                continue;
            }

            foreach ($items as $item) {
                $rows[] = [
                    $orderNumber,
                    $shipTo,
                    $itemCount,
                    $this->sanitizeForSheet((string) ($item['title'] ?? '')),
                    $this->sanitizeForSheet((string) ($item['sku'] ?? '')),
                    $this->sanitizeForSheet((string) ($item['size'] ?? '')),
                    $this->sanitizeForSheet((string) ($item['personalization'] ?? '')),
                ];
            }
        }

        return $rows;
    }

    private function sanitizeForSheet(string $value): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', str_replace(["\t", "\r", "\n"], ' ', $value)));
    }
}
