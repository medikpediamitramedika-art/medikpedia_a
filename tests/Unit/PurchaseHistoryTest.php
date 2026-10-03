<?php

namespace Tests\Unit;

use App\Models\PurchaseHistory;
use PHPUnit\Framework\TestCase;

class PurchaseHistoryTest extends TestCase
{
    public function test_approved_status_uses_discounted_total(): void
    {
        $history = new PurchaseHistory([
            'approval_status' => 'approved',
            'total' => 90000,
            'original_total' => 100000,
        ]);

        $this->assertSame(90000, $history->effective_total);
    }

    public function test_non_approved_status_uses_original_total(): void
    {
        $history = new PurchaseHistory([
            'approval_status' => 'rejected',
            'total' => 90000,
            'original_total' => 100000,
        ]);

        $this->assertSame(100000, $history->effective_total);
    }
}
