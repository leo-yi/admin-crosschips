<?php

declare(strict_types=1);

namespace App\Admin\Repositories;

use App\Models\Category as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Category extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
