<?php

declare(strict_types=1);

namespace App\Services\ExcelImportTasks;

use App\Models\ChipSearchHotKeyword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vtiful\Kernel\Excel;

/**
 * 热门关键词 Excel 导入。
 *
 * 列顺序（首行为表头自动跳过）：Keyword → Sorted → Status（1=启用 0=禁用）。
 * 按 keyword 去重：已存在则更新 sorted/status，不存在则新建。
 */
class ChipSearchHotKeywordImportService implements ImportInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public static function init(string $file): static
    {
        return new static($file);
    }

    /**
     * @return array{created: int, updated: int, skipped: int} 导入统计
     */
    public function handle(): array
    {
        $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0];
        $filePath = pathinfo($this->filePath);
        $config = ['path' => $filePath['dirname']];
        try {
            $excel = (new Excel($config))->openFile($filePath['basename'])->openSheet();
            $i = 0;
            while (($row = $excel->nextRow()) !== null) {
                if ($i === 0) {
                    $i++;

                    continue;
                }
                $formatData = $this->formatData($row);
                if (empty($formatData)) {
                    $stats['skipped']++;

                    continue;
                }
                $keyword = ChipSearchHotKeyword::where('keyword', $formatData['keyword'])->first();
                if ($keyword) {
                    $keyword->update([
                        'sorted' => $formatData['sorted'],
                        'status' => $formatData['status'],
                    ]);
                    $stats['updated']++;
                } else {
                    ChipSearchHotKeyword::create($formatData);
                    $stats['created']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('chip_search_hot_keyword_import_error', [
                'request' => $this->filePath,
                'response' => $e->getMessage(),
            ]);
        }

        return $stats;
    }

    /**
     * @return array{keyword: string, sorted: int, status: int}|array{}
     */
    private function formatData(array $data): array
    {
        $formatData = [
            'keyword' => isset($data[0]) ? trim((string) $data[0]) : '',
            'sorted' => $data[1] ?? 10,
            'status' => $data[2] ?? 1,
        ];
        $validator = Validator::make($formatData, [
            'keyword' => 'required|string|max:100',
            'sorted' => 'integer|min:0|max:999999',
            'status' => 'integer|in:0,1',
        ]);

        return $validator->validated();
    }
}
