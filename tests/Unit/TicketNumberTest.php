<?php

namespace Tests\Unit;

use App\Models\Ticket;
use PHPUnit\Framework\TestCase;

class TicketNumberTest extends TestCase
{
    public function test_generated_ticket_numbers_are_unique_and_have_the_expected_format(): void
    {
        $numbers = [];
        foreach (range(1, 500) as $iteration) {
            $numbers[] = Ticket::generateTicketNumber();
        }

        $this->assertCount(count($numbers), array_unique($numbers));
        foreach ($numbers as $number) {
            $this->assertMatchesRegularExpression('/^TKT-\d{8}-[A-Z0-9]{12}$/', $number);
        }
    }
}
