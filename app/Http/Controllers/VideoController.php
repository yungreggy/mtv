<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoController extends Controller
{
    public function stream(Request $request, $type, $filePath)
{
    $fullPath = storage_path("app/public/{$type}/{$filePath}");

    if (!file_exists($fullPath)) {
        return abort(404, 'File not found.');
    }

    $size = filesize($fullPath);
    $file = fopen($fullPath, 'rb');
    $status = 200;
    $headers = [
        'Content-Type' => 'video/mp4',
        'Accept-Ranges' => 'bytes',
        'Content-Length' => $size,
    ];

    if ($request->headers->has('Range')) {
        $range = $request->header('Range');
        $range = str_replace('bytes=', '', $range);
        list($start, $end) = explode('-', $range);

        if ($end == '') {
            $end = $size - 1;
        }

        $start = intval($start);
        $end = intval($end);
        $length = $end - $start + 1;

        fseek($file, $start);

        $status = 206;
        $headers['Content-Length'] = $length;
        $headers['Content-Range'] = "bytes $start-$end/$size";
    }

    $response = new StreamedResponse(function() use ($file, $length) {
        $buffer = 8192; // Taille du tampon
        while (!feof($file) && $length > 0) {
            $read = ($length > $buffer) ? $buffer : $length;
            echo fread($file, $read);
            $length -= $read;
            ob_flush();
            flush();
        }
        fclose($file);
    }, $status, $headers);

    return $response;
}





}


