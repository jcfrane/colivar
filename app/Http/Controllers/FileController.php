<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToRetrieveMetadata;

class FileController extends Controller
{
    public function upload(UploadRequest $request)
    {
        try {
            $file = $request->file('file');

            // Default Storage driver is gcp, see filesystems.php
            $path = Storage::putFile('files', $file);

            return response()->json([
                'url' => route('file.download', [ 'path' => $path ])
            ], 200);
        } catch (\Exception $exception) {
            logger()->error('File Upload Error', [
                'exception' =>  $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([ 'message' => 'Unknown Error!' ], 500);
        }
    }

    public function download(Request $request)
    {
        try {
            $path = $request->query('path', '');

            return Storage::download($path);
        } catch (UnableToRetrieveMetadata $error) {
            return response()->json([ 'message' => 'File not found!' ], 404);
        } catch (\Exception $exception) {
            logger()->error('File Download Error', [
                'exception' =>  $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            return response()->json([ 'message' => 'Unknown Error!' ], 500);
        }
    }
}
