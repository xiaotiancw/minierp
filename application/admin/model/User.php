<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $table = 'user';
//    public function staff() {
//        return $this->hasOne('Staff','user_id');
//    }
    public function groups() {
        return $this->belongsToMany('Group','auth_group_access','group_id','uid');
    }

}