<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChipRfqProduct
 *
 * @property int $id
 * @property int $rfq_id
 * @property int $product_id
 * @property string $mpn
 * @property int $quantity
 * @property string|null $product_package
 * @property float $target_price
 * @property string|null $manufacturer
 * @property int $manufacturer_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChipRfqProduct extends Model
{
    protected $table = 'chip_rfq_products';

    protected $casts = [
        'rfq_id' => 'int',
        'product_id' => 'int',
        'quantity' => 'int',
        'manufacturer_id' => 'int',
        'target_price' => 'float',
    ];

    protected $fillable = [
        'rfq_id',
        'product_id',
        'mpn',
        'quantity',
        'product_package',
        'target_price',
        'manufacturer',
        'manufacturer_id',
    ];
}
