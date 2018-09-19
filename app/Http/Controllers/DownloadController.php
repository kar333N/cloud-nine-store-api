<?php

namespace App\Http\Controllers;

use App\UserFile;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index($hashUser, $hashFile)
    {
        //http://domain.com/uploaded/[hash_user]/[hash_file]
        $path = storage_path('app/data') . DIRECTORY_SEPARATOR . $hashUser . DIRECTORY_SEPARATOR . $hashFile;

        $fileInfo = UserFile::where('hash_file', $hashFile)->first();

        if (file_exists($path) && $fileInfo) {
            return response()->download($path, $fileInfo->file_name);
        } else {
            return abort(404);
        }


    }
}
