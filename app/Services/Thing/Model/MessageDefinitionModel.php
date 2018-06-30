<?php

namespace App\Services\Thing\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Services\Thing\Model\MessageModel
 *
 * @mixin \Eloquent
 */
class MessageDefinitionModel extends Model
{
    protected $table = 'thing_message_definitions';
    protected $fillable = ['state_id', 'receiver_type', 'receiver_value', 'content'];

    const RECEIVER_TYPE_ROLE            = 'role';
    const RECEIVER_TYPE_TABLE_FIELD     = 'table_field';

    protected const MAP_RECEIVER_TYPE = [
        self::RECEIVER_TYPE_ROLE            => '固定角色',
        self::RECEIVER_TYPE_TABLE_FIELD     => '表字段',
    ];

    public static function getReceiverTypes()
    {
        return self::MAP_RECEIVER_TYPE;
    }

}
