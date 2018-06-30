<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\Thing\Model\State2OperationModel
 *
 * @mixin \Eloquent
 */
class State2OperationModel extends Model
{
    protected $table = 'thing_state_2_operation';
    protected $fillable = ['state_id', 'operation_id'];

}
