<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;

function upload_image_base64($base64, $path, $fileName = null)
{
    list($extension, $content) = explode(';', $base64);
    $tmpExtension = explode('/', $extension);
    $hashTime = sha1(time());
    if (!$fileName) {
        $fileName = $hashTime . '_' . Carbon::now()->timestamp . '.' . $tmpExtension[1];
    }
    $content = explode(',', $content)[1];
    Storage::put($path . '/' . $fileName, base64_decode($content), 'public');

    return Storage::url($path . '/' . $fileName);
}

function is_base64image($string)
{
    if (empty($string)) {
        return false;
    }

    $explode = explode(',', $string);

    if (!isset($explode[1])) {
        return false;
    }

    $imgdata = base64_decode($explode[1]);
    $mimeType = finfo_buffer(finfo_open(), $imgdata, FILEINFO_MIME_TYPE);
    $mimeType = explode('/', $mimeType);

    if (!isset($mimeType[0])) {
        return false;
    }

    return $mimeType[0] == 'image';
}

function base64image_mimes($value, $parameters)
{
    $explode = explode(',', $value);
    $parameters = is_array($parameters) ? $parameters : explode(',', $parameters);

    if (!isset($explode[0])) {
        return false;
    }

    $pattern = '/^data:image\/(\w+);base64$/';
    preg_match($pattern, $explode[0], $matches);
    if ($matches) {
        return in_array($matches[1], $parameters);
    }

    return false;
}
