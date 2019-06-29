<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

   public function update(User $currentUser,User $user){
       return $currentUser->id ==$user->id;
   }

   public function destroy(User $currenUsr,User $user){
       return $currenUsr->is_admin && $currenUsr->id !== $user->id;
   }

   public function follow(User $currenUsr,User $user){
       return  $currenUsr->id !== $user->id;
   }
}
