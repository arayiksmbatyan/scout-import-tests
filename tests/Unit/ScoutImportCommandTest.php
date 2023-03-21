<?php

namespace Tests\Unit;

use Tests\TestCase;


class ScoutImportCommandTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_scout_import()
    {
        $this->artisan("scout:import", ['model' => 'App\Models\User'])->assertSuccessful();
    }
}
