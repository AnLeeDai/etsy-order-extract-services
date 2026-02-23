<?php

namespace App\Services;

class EtsyOrderPdfParser
{
    /**
     * Parse Etsy order text extracted from PDF and return highlighted fields.
     *
     * @return array{
     *     order_number: string|null,
     *     item_count: int|null,
     *     ship_to: array{
     *         name: string|null,
     *         lines: array<int, string>,
     *         address_lines: array<int, string>,
     *         full: string
     *     },
     *     items: array<int, array{
     *         title: string|null,
     *         sku: string|null,
     *         size: string|null,
     *         personalization: string|null,
     *         quantity: int|null,
     *         currency: string|null,
     *         unit_price: string|null
     *     }>
     * }
     */
    public function parse(string $text): array
    {
        $lines = $this->normalizeLines($text);
        $sectionLines = $this->extractPrimaryOrderSection($lines);

        $orderNumber = $this->extractOrderNumber($sectionLines);
        $itemCount = $this->extractItemCount($sectionLines);
        $shipToLines = $this->extractShipToLines($sectionLines);
        $items = $this->extractItems($sectionLines, $itemCount);

        return [
            'order_number' => $orderNumber,
            'item_count' => $itemCount,
            'ship_to' => [
                'name' => $shipToLines[0] ?? null,
                'lines' => $shipToLines,
                'address_lines' => array_slice($shipToLines, 1),
                'full' => implode(', ', $shipToLines),
            ],
            'items' => $items,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function normalizeLines(string $text): array
    {
        $rawLines = preg_split('/\R/u', $text) ?: [];
        $lines = [];

        foreach ($rawLines as $line) {
            $line = trim((string) preg_replace('/\s+/u', ' ', $line));

            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return $lines;
    }

    /**
     * Many Etsy PDFs contain both local language and English sections.
     * Prefer the last "Order #" block, which is typically the English one.
     *
     * @param array<int, string> $lines
     * @return array<int, string>
     */
    private function extractPrimaryOrderSection(array $lines): array
    {
        $startIndex = null;

        foreach ($lines as $index => $line) {
            if (preg_match('/^Order\s*#/i', $line)) {
                $startIndex = $index;
            }
        }

        if ($startIndex === null) {
            foreach ($lines as $index => $line) {
                if (preg_match('/^Bestellung\s*#/iu', $line)) {
                    $startIndex = $index;
                }
            }
        }

        return $startIndex === null ? $lines : array_slice($lines, $startIndex);
    }

    /**
     * @param array<int, string> $lines
     */
    private function extractOrderNumber(array $lines): ?string
    {
        foreach ($lines as $line) {
            if (preg_match('/(?:Order|Bestellung)\s*#\s*([A-Za-z0-9\-]+)/iu', $line, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * @param array<int, string> $lines
     */
    private function extractItemCount(array $lines): ?int
    {
        foreach ($lines as $line) {
            if (preg_match('/^(\d+)\s+(items?|artikel)$/iu', $line, $matches)) {
                return (int) $matches[1];
            }
        }

        return null;
    }

    /**
     * @param array<int, string> $lines
     * @return array<int, string>
     */
    private function extractShipToLines(array $lines): array
    {
        $startIndex = $this->findLineIndex($lines, [
            '/^Ship to$/iu',
            '/^Versand an$/iu',
        ]);

        if ($startIndex === null) {
            return [];
        }

        $shipToLines = [];

        for ($index = $startIndex + 1; $index < count($lines); $index++) {
            $line = $lines[$index];

            if ($this->isShipToStopLine($line)) {
                break;
            }

            $shipToLines[] = $line;
        }

        return $shipToLines;
    }

    /**
     * @param array<int, string> $lines
     * @param int|null $expectedCount
     * @return array<int, array{
     *     title: string|null,
     *     sku: string|null,
     *     size: string|null,
     *     personalization: string|null,
     *     quantity: int|null,
     *     currency: string|null,
     *     unit_price: string|null
     * }>
     */
    private function extractItems(array $lines, ?int $expectedCount): array
    {
        $items = [];
        $cursor = $this->findLineIndex($lines, ['/^\d+\s+(items?|artikel)$/iu']);
        $cursor = $cursor === null ? 0 : $cursor + 1;
        $totalLines = count($lines);

        while ($cursor < $totalLines) {
            $line = $lines[$cursor];

            if ($this->isTotalsLine($line) || $this->isFooterLine($line)) {
                break;
            }

            $titleLines = [];

            while ($cursor < $totalLines) {
                $line = $lines[$cursor];

                if ($this->isItemMetadataLine($line) || $this->isItemPriceLine($line) || $this->isTotalsLine($line)) {
                    break;
                }

                if ($this->isFooterLine($line)) {
                    break 2;
                }

                $titleLines[] = $line;
                $cursor++;
            }

            if ($titleLines === []) {
                $cursor++;
                continue;
            }

            $item = [
                'title' => implode(' ', $titleLines),
                'sku' => null,
                'size' => null,
                'personalization' => null,
                'quantity' => null,
                'currency' => null,
                'unit_price' => null,
            ];

            while ($cursor < $totalLines) {
                $line = $lines[$cursor];

                if ($this->isTotalsLine($line)) {
                    break;
                }

                if ($this->isFooterLine($line)) {
                    $cursor = $totalLines;
                    break;
                }

                if (preg_match('/^SKU:\s*(.+)$/iu', $line, $matches)) {
                    $item['sku'] = $matches[1];
                    $cursor++;
                    continue;
                }

                if (preg_match('/^Size:\s*(.+)$/iu', $line, $matches)) {
                    $item['size'] = $matches[1];
                    $cursor++;
                    continue;
                }

                if (preg_match('/^Personalization:\s*(.+)$/iu', $line, $matches)) {
                    $item['personalization'] = $matches[1];
                    $cursor++;
                    continue;
                }

                if (preg_match('/^(\d+)\s*x\s*([A-Z]{3})\s*([\d.,]+)$/', $line, $matches)) {
                    $item['quantity'] = (int) $matches[1];
                    $item['currency'] = $matches[2];
                    $item['unit_price'] = $matches[3];
                    $cursor++;
                    break;
                }

                break;
            }

            $items[] = $item;

            if ($expectedCount !== null && count($items) >= $expectedCount) {
                break;
            }
        }

        return $items;
    }

    private function isShipToStopLine(string $line): bool
    {
        return (bool) preg_match(
            '/^(Scheduled to ship by|Versand geplant|Shop|Order date|Bestelldatum|Payment method|Zahlungsmethode|\d+\s+(items?|artikel))$/iu',
            $line
        );
    }

    private function isTotalsLine(string $line): bool
    {
        return (bool) preg_match(
            '/^(Item total|Shop discount|Shipping total|Subtotal|Tax|Order total|Gesamtbetrag Artikel|Gesamtbetrag Versand|Zwischensumme|Steuer|Gesamtsumme der Bestellung)\b/iu',
            $line
        );
    }

    private function isFooterLine(string $line): bool
    {
        return (bool) preg_match('/^(Do the green thing|Gef|Klimaschutzplan)\b/iu', $line);
    }

    private function isItemMetadataLine(string $line): bool
    {
        return (bool) preg_match('/^(SKU|Size|Personalization):\s*/iu', $line);
    }

    private function isItemPriceLine(string $line): bool
    {
        return (bool) preg_match('/^\d+\s*x\s*[A-Z]{3}\s*[\d.,]+$/', $line);
    }

    /**
     * @param array<int, string> $lines
     * @param array<int, string> $patterns
     */
    private function findLineIndex(array $lines, array $patterns): ?int
    {
        foreach ($lines as $index => $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line)) {
                    return $index;
                }
            }
        }

        return null;
    }
}
