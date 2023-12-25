<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    public function test_file_existence()
    {
        Storage::fake('gcs');

        $response = $this->json('POST', '/api/upload');

        $response->assertStatus(422);
        $response->assertJsonPath('errors.file.0', 'The file field is required.');
    }
    public function test_file_type_validation()
    {
        Storage::fake('gcs');

        $response = $this->json('POST', '/api/upload', [
            'file' =>  UploadedFile::fake()->image('photo1.jpg'),
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.file.0', 'The file must be a file of type: text/plain, text/rtf.');
    }
    public function test_file_size_validation()
    {
        Storage::fake('gcs');

        $response = $this->json('POST', '/api/upload', [
            'file' =>  UploadedFile::fake()->create('sample.txt')->size('5000'),
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.file.0', 'The file must not be greater than 2048 kilobytes.');
    }

    public function test_success_file_upload()
    {
        Storage::fake('gcs');

        $response = $this->json('POST', '/api/upload', [
            'file' =>  UploadedFile::fake()->create('sample.txt')->size(1500),
        ]);

        $response->assertStatus(200);
    }

    // Testing uses the actual configuration used if not overridden in .env.testing
    // For testing purposes, we will be using the cloud storage setup we using for development
    public function test_failed_download()
    {
        $response = $this->json('GET', '/api/download?path=not_existing.txt');
        $response->dump();

        $response->assertStatus(404);
        $response->assertJsonPath('message', 'File not found!');
    }
}
