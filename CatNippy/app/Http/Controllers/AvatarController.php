<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'max:2048|mimes:jpg,bmp,png,svg,gif'
        ]);
        $file = $request->file('avatar');
        
        // Relies on the supplied filename extension.
        // It does not verify that the file content is a real image.
        //$extension = strtolower($file->getClientOriginalExtension());
        /*
        $extension = $file->getClientOriginalExtension();
        
        $disallowedExtensions = [
            'exe', 'bat', 'cmd', 'com', 'msi', 'sh', 'ps1',
            'php', 'phtml', 'js', 'html', 'htm', 'asp', 'aspx',
            'jsp', 'py', 'rb', 'pl', 'cgi', 'dll', 'so', 'dylib',
            'zip', 'rar', '7z', 'tar', 'gz',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'mp3', 'mp4', 'avi', 'mov', 'mkv',
            'txt', 'csv', 'xml', 'json', 'sql',
        ];

        if (in_array($extension, $disallowedExtensions, true)) {
            return back()->withErrors([
                'avatar' => 'Please choose a JPG, PNG, GIF, WEBP, or SVG file.',
            ]);
        }*/
        /*
        $allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        ];

        if (!in_array($extension, $allowedExtensions, true)) {
            return back()->withErrors([
                'avatar' => 'Please choose a JPG, PNG, GIF, WEBP, or SVG file.',
            ]);
        }
*/
        // Generate our own filename rather than using the uploaded filename.
        $path = $file->storeAs(
            'avatars',
            Str::uuid() . '.' . $extension,
            'public'
        );

        $user = $request->user();
        $previousPath = $user->avatar_path;

        $user->avatar_path = $path;
        $user->save();

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return back()->with('status', 'avatar-updated');
    }
}
