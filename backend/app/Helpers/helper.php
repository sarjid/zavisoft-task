<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

if (!function_exists('file_upload')) {
    function file_upload(UploadedFile $file, string $directory = 'uploads', string $prefix = ''): string
    {
        $directory = trim($directory, '/');
        $uploadDir = public_path($directory);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = ($prefix ? $prefix . '-' : '') . Str::uuid() . ($extension ? '.' . $extension : '');

        $file->move($uploadDir, $fileName);

        return '/' . $directory . '/' . $fileName;
    }
}

if (!function_exists('delete_public_file')) {
    function delete_public_file(?string $path): void
    {
        if (!$path) {
            return;
        }

        $relativePath = ltrim($path, '/');
        $fullPath = public_path($relativePath);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
