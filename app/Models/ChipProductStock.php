<?php

namespace App\Models;

use App\Enum\StockTypeEnum;
use Carbon\Carbon;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Class ChipProductStock
 *
 * @property int $id
 * @property int $product_id
 * @property int $stock_type
 * @property int|null $stock
 * @property float $price
 * @property string $currency_code
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class ChipProductStock extends Model
{
    const COMING = 1;

    public const IMMEDIATELY = 2;

    public const CUSTOMER = 3;

    public const HOT_SALE = 4;

    public const STOCK_TYPE_MAP = [
        self::COMING => StockTypeEnum::COMING->value,
        self::IMMEDIATELY => StockTypeEnum::IMMEDIATELY->value,
        self::CUSTOMER => StockTypeEnum::CUSTOMER->value,
        self::HOT_SALE => StockTypeEnum::HOT_SALE->value,
    ];

    use HasDateTimeFormatter;

    protected $table = 'chip_product_stock';

    protected $casts = [
        'product_id' => 'int',
        'stock_type' => 'int',
        'stock' => 'int',
        'price' => 'float',
    ];

    protected $fillable = [
        'product_id',
        'stock_type',
        'stock',
        'price',
        'currency_code',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(ChipProduct::class, 'product_id', 'id');
    }

    public function manufacturer(): HasOneThrough
    {
        return $this->hasOneThrough(ChipManufacturer::class, ChipProduct::class, 'id', 'id', 'product_id', 'manufacturer_id');
    }
}
