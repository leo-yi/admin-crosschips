<?php

namespace App\Admin\Repositories;

use App\Models\ChipManufacturer as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class ChipManufacturer extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
