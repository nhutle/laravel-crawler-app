<?php

namespace Tests;

use App\Models\User;

class StatisticsTest extends TestCase
{
    /**
     * Test non authenticated User can not access statistics.
     *
     * @return void
     */
    public function testNonAuthenticatedUserCanNotAccessStatistics()
    {
        $response = $this->get('/statistics');
        $response->assertStatus(302);
    }

    /**
     * Test authenticated User can access upload.
     */
    public function testAuthenticatedUserCanAccessUpload()
    {
        $user = User::where('username', 'nhutle')->first();

        $this->actingAs($user);
        $response = $this->get('/statistics');
        $response->assertStatus(200);
        $response->assertViewIs('statistics');
        $response->assertViewHas('statistics');
    }
}
