<?php
//用户授权策略类
//
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    //用户更新时候的权限验证
    public function update(User $currentUser, User $user)
    {
	return $currentUser->id === $user->id;
    }
    
    //删除用户
    public function destroy(User $currentUser, User $user)
    {
	//只有当前用户拥有管理权限  而且不是自己时候才显示链接
    	return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
