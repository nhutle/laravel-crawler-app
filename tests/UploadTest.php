<?php

namespace Tests;

use App\Models\User;

class UploadTest extends TestCase
{
    /**
     * Test non authenticated User can not access upload.
     *
     * @return void
     */
    public function testNonAuthenticatedUserCanNotAccessUpload()
    {
        $response = $this->get('/upload');
        $response->assertStatus(302);
    }

    /**
     * Test authenticated User can access upload.
     */
    public function testAuthenticatedUserCanAccessUpload()
    {
        $user = User::where('username', 'nhutle')->first();

        $this->actingAs($user);
        $response = $this->get('/upload');
        $response->assertStatus(200);
    }
}
