<?php

namespace App\Models;

use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Class ChipProduct
 *
 * @property int $id
 * @property string $mpn
 * @property string $sku
 * @property string $img
 * @property string $product_package
 * @property string $product_desc
 * @property string $price_unit
 * @property int $standard_packing_quantity
 * @property int $price_break_1
 * @property float $price_1
 * @property string $price_1_currency
 * @property int $price_break_2
 * @property float $price_2
 * @property string $price_2_currency
 * @property int $price_break_3
 * @property float $price_3
 * @property string $price_3_currency
 * @property int $price_break_4
 * @property float $price_4
 * @property string $price_4_currency
 * @property int $price_break_5
 * @property float $price_5
 * @property string $price_5_currency
 * @property int $price_break_6
 * @property float $price_6
 * @property string $price_6_currency
 * @property int $category_one_id
 * @property int $category_two_id
 * @property int $manufacturer_id
 * @property int $is_rohs
 * @property string $data_sheet_url
 * @property string $specifications
 * @property int $quantity
 * @property float $weight
 * @property int $is_battery
 * @property int $in_stock
 * @property string|null $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipProduct extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'chip_product';

    protected $casts = [
        'standard_packing_quantity' => 'int',
        'price_break_1' => 'int',
        'price_1' => 'float',
        'price_break_2' => 'int',
        'price_2' => 'float',
        'price_break_3' => 'int',
        'price_3' => 'float',
        'price_break_4' => 'int',
        'price_4' => 'float',
        'price_break_5' => 'int',
        'price_5' => 'float',
        'price_break_6' => 'int',
        'price_6' => 'float',
        'category_one_id' => 'int',
        'category_two_id' => 'int',
        'manufacturer_id' => 'int',
        'is_rohs' => 'int',
        'specifications' => 'string',
        'quantity' => 'int',
        'in_stock' => 'int',
        'weight' => 'float',
    ];

    protected $fillable = [
        'mpn',
        'sku',
        'img',
        'product_package',
        'product_desc',
        'price_unit',
        'standard_packing_quantity',
        'price_break_1',
        'price_1',
        'price_1_currency',
        'price_break_2',
        'price_2',
        'price_2_currency',
        'price_break_3',
        'price_3',
        'price_3_currency',
        'price_break_4',
        'price_4',
        'price_4_currency',
        'price_break_5',
        'price_5',
        'price_5_currency',
        'price_break_6',
        'price_6',
        'price_6_currency',
        'category_one_id',
        'category_two_id',
        'manufacturer_id',
        'is_rohs',
        'data_sheet_url',
        'specifications',
        'quantity',
        'in_stock',
    ];

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(ChipManufacturer::class, 'manufacturer_id');
    }

    public function categoryOne(): BelongsTo
    {
        return $this->belongsTo(ChipCategory::class, 'category_one_id');
    }

    public function categoryTwo(): BelongsTo
    {
        return $this->belongsTo(ChipCategory::class, 'category_two_id');
    }

    /**
     * 通过 S3 获取 datasheet 的临时访问 URL
     */
    public static function getDataSheet(string $dataSheetUrl): string
    {
        if (! config('filesystems.enable_s3')) {
            return $dataSheetUrl;
        }
        try {
            $pathInfo = pathinfo($dataSheetUrl);
            $fileName = md5($pathInfo['filename']).'.'.$pathInfo['extension'];
            $path = 'sheet/'.$fileName;

            return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));
        } catch (Exception $e) {
            return $dataSheetUrl;
        }
    }
}
