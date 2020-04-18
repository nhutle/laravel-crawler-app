<?php

namespace Tests;

class LoginTest extends TestCase
{
    /**
     * Test user can see login form.
     *
     * @return void
     */
    public function testUserCanSeeLoginForm()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function testUserCanLoginWithProvidedCredential()
    {
        $response = $this->post('/login', [
            'username' => 'nhutle',
            'password' => 'nhutle',
        ]);

        $response->assertRedirect('/statistics');
    }
}
