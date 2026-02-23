<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

class PDFConvertControllerTest extends TestCase
{
    #[Test]
    public function it_supports_uploading_multiple_pdf_files_in_one_request(): void
    {
        $pdfText1 = <<<'TEXT'
Order #5000000001
Ship to
Alice Smith
1 Main St
Dallas, TX 75001
United States
Scheduled to ship by
Feb 23, 2026
1 item
Product One
SKU: SKU-ONE
Size: White 11oz
Personalization: Alice
1 x USD 10.00
Item total USD 10.00
Order total USD 10.00
TEXT;

        $pdfText2 = <<<'TEXT'
Order #5000000002
Ship to
Bob Lee
99 Lake View
Orlando, FL 32801
United States
Scheduled to ship by
Feb 23, 2026
1 item
Product Two
SKU: SKU-TWO
Size: Black 11oz
Personalization: Bob
1 x USD 20.00
Item total USD 20.00
Order total USD 20.00
TEXT;

        $documentOne = \Mockery::mock(Document::class);
        $documentOne->shouldReceive('getText')->once()->andReturn($pdfText1);

        $documentTwo = \Mockery::mock(Document::class);
        $documentTwo->shouldReceive('getText')->once()->andReturn($pdfText2);

        $this->mock(Parser::class, function (MockInterface $mock) use ($documentOne, $documentTwo): void {
            $mock->shouldReceive('parseFile')
                ->twice()
                ->andReturn($documentOne, $documentTwo);
        });

        $response = $this->post('/extract', [
            'pdf_files' => [
                UploadedFile::fake()->create('first.pdf', 100, 'application/pdf'),
                UploadedFile::fake()->create('second.pdf', 100, 'application/pdf'),
            ],
        ]);

        $response->assertOk();
        $response->assertViewHas('results', function ($results): bool {
            if (! is_array($results) || count($results) !== 2) {
                return false;
            }

            if (($results[0]['success'] ?? false) !== true || ($results[1]['success'] ?? false) !== true) {
                return false;
            }

            $firstOrder = $results[0]['result']['order_number'] ?? null;
            $secondOrder = $results[1]['result']['order_number'] ?? null;

            return $firstOrder === '5000000001' && $secondOrder === '5000000002';
        });
        $response->assertViewHas('sheetHeaders', function ($headers): bool {
            if (! is_array($headers)) {
                return false;
            }

            return in_array('order_number', $headers, true)
                && in_array('ship_to', $headers, true)
                && in_array('personalization', $headers, true);
        });
        $response->assertViewHas('sheetRows', function ($rows): bool {
            if (! is_array($rows) || count($rows) !== 2) {
                return false;
            }

            return $rows[0][0] === '5000000001' && $rows[1][0] === '5000000002';
        });
    }
}
