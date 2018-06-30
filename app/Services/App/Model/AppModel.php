<?php

namespace App\Services\App\Model;

use App\Services\_Base\SimpleStateModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 用户应用模型
 * 
 * Class AppModel
 *
 * @package App\Services\App\Model
 * @mixin \Eloquent
 */
class AppModel extends SimpleStateModel
{
    use SoftDeletes;

    protected $table = 'apps';

    protected $fillable = ['user_id', 'name', 'description', 'state'];

}
