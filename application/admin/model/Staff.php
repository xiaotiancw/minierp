<?php
namespace app\admin\model;

use think\Model;

class Staff extends Model
{
    public function user() {
        return $this->belongsTo('User','staff_id');
    }
}