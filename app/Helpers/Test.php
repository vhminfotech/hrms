<?php
//app/Helpers/Envato/User.php
namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Users;

class Test {
    
    public function admindetails() {
        $objUsers = new Users();
        $data['users'] = $objUsers->getAdmindetails();
         
        return (isset($user->username) ? $user->username : '');
    }
}