<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
class FileListController extends Controller
{
    //
    public function listFiles()
    {
        $files = Storage::disk('public')->files('video');
    
        $files = collect($files)->map(fn($file) => [
            'name' => basename($file),
            'url' => asset("storage/video/" . basename($file)),
        ]);
    
        return response()->json($files);
    }
    public function deleteFiles(Request $request)
    {
        $files = Storage::disk('public')->files('video');
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
        
        return redirect('/')->with('message', 'All files deleted successfully.');
    }
}


