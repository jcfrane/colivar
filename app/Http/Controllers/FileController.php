<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToRetrieveMetadata;

class FileController extends Controller
{
    /**
     * Note, I do not utilize any model saving here since the task at hand
     * only requires that I save the file in GCP and return a downloadable content.
     *
     * For more complex file manipulation it is advised to save the file metadata in the
     * database.
     *
     * @param UploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
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
