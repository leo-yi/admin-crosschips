<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * 下载文件到服务器本地
     *
     * @return string 本地文件路径
     */
    public function downloadFile($cloudFilePath): string
    {
        $filePathInfo = pathinfo($cloudFilePath);

        // 创建临时目录
        $tempDir = storage_path('app/temp/'.date('Ymd'));
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir);
        }
        $file = Storage::disk('admin')->get($cloudFilePath);
        $tempFilePath = $tempDir.'/'.$filePathInfo['basename'];

        File::put($tempFilePath, $file);

        return $tempFilePath;
    }

    public function cleanupFile($tempFilePath): void
    {
        if (File::exists($tempFilePath)) {
            File::delete($tempFilePath);
        }
    }
}
