<?php

namespace Yl20181120\DcatExtendTenancy\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;
use Yl20181120\DcatExtendTenancy\Models\Domain as DomainModel;

class Domain extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = DomainModel::class;
}
