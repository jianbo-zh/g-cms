<?php

namespace App\Services\Thing\Model;

use App\Services\_Base\SimpleStateModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Services\Thing\Model\ThingModel
 *
 * @mixin \Eloquent
 */
class ThingModel extends SimpleStateModel
{
    use SoftDeletes;

    protected $table = 'things';
    protected $fillable = ['app_id', 'name', 'description', 'table_name'];

    const THING_MODEL_PREFIX = 'thing_z_';

    /**
     * 事物的字段
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(FieldModel::class, 'thing_id');
    }
}
