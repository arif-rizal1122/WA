<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{


   public function testRegister()
   {
       // Simulate picture upload
       Storage::fake('post-images');
       $picture = UploadedFile::fake()->image('test.png');
   
       // Make POST request to register user
       $this->post('/api/auth/register', [
           'name' => 'Test User',
           'username' => 'testuser',
           'email' => 'test@example.com',
           'phone' => '1234567890',
           'password' => 'password',
           'picture' => $picture,
       ])->assertStatus(201)->assertJson([
         "data" => [
             'name' => 'Test User',
             'username' => 'testuser',
             'email' => 'test@example.com',
             'phone' => '1234567890',
             'picture' => 'post-images/' . $picture->hashName(),
         ],
     ]);
     


     
   }




   





       



}
