<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
    public function staff() {
        return $this->hasOne('Staff','user_id');
    }
}