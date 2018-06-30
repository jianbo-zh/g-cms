<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\Thing\Model\StateModel
 *
 * @mixin \Eloquent
 */
class StateModel extends Model
{
    protected $table = 'thing_states';

    protected $fillable = ['thing_id', 'name', 'state'];


    public function conditions()
    {
        $this->hasMany(StateConditionModel::class, 'state_id');
    }

}
