<?php

namespace Yl20181120\DcatExtendTenancy\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'domain';
    protected $keyType = 'string';
}
