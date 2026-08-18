<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomepagePerformanceTest extends TestCase
{
    /**
     * Regression guard: fails if a future change reintroduces N+1 queries or
     * un-cached repeated lookups (this is what made the homepage slow before).
     */
    public function test_homepage_query_count_stays_low(): void
    {
        $queryCount = 0;
        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertLessThan(15, $queryCount, "Homepage issued {$queryCount} queries — expected under 15. Check for new N+1 queries or un-cached lookups.");
    }

    public function test_repeated_homepage_requests_do_not_requery_settings(): void
    {
        $this->get('/');

        $queryCount = 0;
        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $this->get('/');

        // Settings should come from cache on the second request, not a fresh query.
        $this->assertLessThan(10, $queryCount, "Second homepage request issued {$queryCount} queries — settings cache may not be working.");
    }
}
