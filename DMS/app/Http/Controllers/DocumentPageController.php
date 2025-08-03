<?php

namespace App\Http\Controllers;

use App\Models\DocumentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class DocumentPageController extends Controller
{
    use AuthorizesRequests;

    public function download(DocumentPage $documentPage)
    {
        $this->authorize('download', $documentPage);

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
            'page' => [
                'required',
                'file',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());
                    $sizeInMB = $value->getSize() / 1024 / 1024; // convert bytes to MB

                    if (in_array($extension, ['png', 'jpeg', 'jpg']) && $sizeInMB > 15) {
                        $fail('Image files must not exceed 15MB.');
                    } elseif ($extension === 'pdf' && $sizeInMB > 150) {
                        $fail('PDF files must not exceed 150MB.');
                    } elseif (in_array($extension, ['docx', 'pptx', 'xlsx']) && $sizeInMB > 2048) {
                        $fail('Document files must not exceed 2GB.');
                    } elseif (!in_array($extension, ['png', 'jpeg', 'jpg', 'pdf', 'docx', 'pptx', 'xlsx'])) {
                        $fail('Invalid file type: ' . $extension);
                    }
                },
            ],
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
