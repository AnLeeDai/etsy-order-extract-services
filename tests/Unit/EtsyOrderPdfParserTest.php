<?php

namespace Tests\Unit;

use App\Services\EtsyOrderPdfParser;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EtsyOrderPdfParserTest extends TestCase
{
    #[Test]
    public function it_extracts_highlighted_fields_from_etsy_order_text(): void
    {
        $text = <<<'TEXT'
Order #3977723338
Heather Vieira (HeathVieira13)
Ship to
Heather Vieira
23 Carbonell Place
ST. AUGUSTINE, FL 32095
United States
Scheduled to ship by
Feb 23, 2026
Shop
FrewandCreations
Order date
Feb 18, 2026
Payment method
Paid via Etsy Payments
2 items
Personalized Makeup Mug, Custom Name Coffee Mug, Beauty Lover Gift
SKU: NBD26011702
Size: Black Accent 11oz
Personalization: Amanda
1 x USD 29.99
Personalized Makeup Mug, Custom Name Coffee Mug, Beauty Lover Gift
SKU: NBD26011702
Size: Black Accent 11oz
Personalization: Heather
1 x USD 29.99
Item total USD 59.98
Shop discount - USD 23.99
Shipping total USD 8.98
Subtotal USD 44.97
Tax USD 2.92
Order total USD 47.89
TEXT;

        $parser = app(EtsyOrderPdfParser::class);
        $result = $parser->parse($text);

        $this->assertSame('3977723338', $result['order_number']);
        $this->assertSame(2, $result['item_count']);
        $this->assertSame('Heather Vieira', $result['ship_to']['name']);
        $this->assertSame('23 Carbonell Place', $result['ship_to']['address_lines'][0]);
        $this->assertCount(2, $result['items']);
        $this->assertSame('Amanda', $result['items'][0]['personalization']);
        $this->assertSame('Heather', $result['items'][1]['personalization']);
    }
}
