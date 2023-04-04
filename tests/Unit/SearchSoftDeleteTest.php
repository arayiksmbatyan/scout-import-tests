<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_soft_delete()
    {
        $user = User::factory()->create([
            'name' => 'Laravel Typsense',
            'email' => 'typesense@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->delete();

        $this->assertCount(0, User::all());

        $models = User::search('typesense@example.com')->get();

        $this->assertCount(0, $models);
    }
}
