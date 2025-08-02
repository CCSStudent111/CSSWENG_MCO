<?php

namespace App\Http\Controllers;

use App\Models\DocumentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentPageController extends Controller
{
    public function download(DocumentPage $documentPage)
    {
        if (!Storage::disk('public')->exists($documentPage->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($documentPage->file_path, $documentPage->original_name);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentPage $documentPage)
    {
        $request->validate([
            'page' => 'required|file'
        ]);

        // Delete old file from 'public' disk
        if (Storage::disk('public')->exists($documentPage->file_path)) {
            Storage::disk('public')->delete($documentPage->file_path);
        }

        // Store new file to 'public' disk
        $newfile_path = $request->file('page')->store('document-pages', 'public');

        // Update database
        $documentPage->update([
            'file_path' => $newfile_path,
            'original_name' => $request->file('page')->getClientOriginalName()
        ]);

        return redirect()->back()->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPage $documentPage)
    {
        if (Storage::disk('public')->exists($documentPage->file_path)) {
            Storage::disk('public')->delete($documentPage->file_path);
        }

        // Delete record from DB
        $documentPage->delete();

        return back()->with('success', 'Page deleted successfully.');
    }
}
