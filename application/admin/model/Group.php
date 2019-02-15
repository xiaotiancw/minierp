<?php
namespace app\admin\model;
use think\Model;
/**
 * Description of Group
 *
 * @author XiaoT
 */
class Group extends Model{
    protected $table = 'auth_group';

    public function users() {
        return $this->belongsToMany('User', 'auth_group_access', 'group_id', 'uid');
    }

}
