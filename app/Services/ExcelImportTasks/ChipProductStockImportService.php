<?php

namespace App\Services\ExcelImportTasks;

use App\Enum\StockTypeEnum;
use App\Models\ChipCategory;
use App\Models\ChipManufacturer;
use App\Models\ChipProduct;
use App\Models\ChipProductStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Vtiful\Kernel\Excel;

class ChipProductStockImportService implements ImportInterface
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

    public function handle(): void
    {
        // 获取全量的 manufacturer
        $manufacturers = ChipManufacturer::select(['id', 'mnf_name'])->get();
        // 获取全量的 category
        $categories = ChipCategory::select(['id', 'category_name'])->get();
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
                    continue;
                }
                $this->dealRow($formatData, $manufacturers, $categories);
            }
        } catch (\Exception $e) {
            Log::error('chip_product_stock_import_error', [
                'request' => $this->filePath,
                'response' => $e->getMessage(),
            ]);
        }
    }

    public function dealRow(array $data, Collection $manufacturers, Collection $categories): void
    {
        // 查找芯片产品
        $chipProduct = ChipProduct::where('mpn', $data['mpn'])->first();
        if (! $chipProduct) {
            $chipProduct = $this->getChipProduct($manufacturers, $data, $categories);
        }
        // 写进库存表
        $stockData = [
            'product_id' => $chipProduct->id,
            'stock' => $data['quantity'],
            'stock_type' => StockTypeEnum::getCodeByName($data['lead_time']),
            'price' => $data['price'],
            'currency_code' => $data['currency'],
        ];
        ChipProductStock::create($stockData);
    }

    private function formatData($data)
    {

        $formatData = [
            'mpn' => $data[0],
            'quantity' => $data[1],
            'manufacturer' => $data[2],
            'package' => $data[3],
            'publish' => $data[4],
            'date_code' => $data[5],
            'lead_time' => $data[6],
            'price' => $data[7],
            'currency' => $data[8],
            'first_category' => $data[9],
            'second_category' => $data[10],
            'description' => $data[11],
        ];
        $validator = Validator::make($formatData, [
            'mpn' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1|max:9999999',
            'manufacturer' => 'string|max:50',
            'package' => 'string|max:50',
            'publish' => 'string|max:50',
            'date_code' => 'string',
            'lead_time' => 'string',
            'price' => 'numeric',
            'currency' => 'string',
            'first_category' => 'string|max:50',
            'second_category' => 'string|max:50',
            'description' => 'string|max:255',
        ]);

        return $validator->validated();
    }

    public function getChipProduct(Collection $manufacturers, array $data, Collection $categories): ChipProduct
    {
        // 查询芯片厂商
        $manufacturerId = 0;
        $manufacturer = $manufacturers->where('mnf_name', $data['manufacturer'])->first();
        if (isset($manufacturer->id)) {
            $manufacturerId = $manufacturer->id;
        }
        // 查询一级分类
        $categoryOneId = 0;
        $categoryOne = $categories->where('category_name', $data['first_category'])->first();
        if (isset($categoryOne->id)) {
            $categoryOneId = $categoryOne->id;
        }
        // 查询二级分类
        $categoryTwoId = 0;
        $categoryTwo = $categories->where('category_name', $data['second_category'])->first();
        if (isset($categoryTwo->id)) {
            $categoryTwoId = $categoryTwo->id;
        }
        // 创建一个新的产品
        $productData = [
            'mpn' => $data['mpn'],
            'manufacturer' => $manufacturerId,
            'product_package' => $data['package'],
            'price_1' => $data['price'],
            'price_1_currency' => $data['currency'],
            'price_break_1' => 1,
            'category_one_id' => $categoryOneId,
            'category_two_id' => $categoryTwoId,
            'product_desc' => $data['description'],
        ];
        if ($data['lead_time'] == StockTypeEnum::IMMEDIATELY->value) {
            $productData['in_stock'] = $data['quantity'];
        }

        return ChipProduct::create($productData);
    }
}
