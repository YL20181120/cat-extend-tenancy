<?php

namespace Yl20181120\DcatExtendTenancy\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $keyType = 'string';

    protected $casts = [
        'data' => 'json',
        'expired_at' => 'datetime:Y-m-d'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function domains()
    {
        return $this->hasMany(Domain::class, 'tenant_id');
    }

    protected static function boot()
    {
        parent::boot();
    }
}
