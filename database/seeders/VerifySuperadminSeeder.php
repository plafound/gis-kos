<?php

namespace Database\Seeders;

use App\Models\User;
use App\Utils\Constant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VerifySuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin_role_id = DB::table('roles')->where('name', Constant::ROLE_SUPERADMIN)->first()->id;
        $superadmin_users = DB::table('model_has_roles')->where('role_id', $superadmin_role_id)->get();

        foreach ($superadmin_users as $user) {
            $data = User::where('id', $user->model_id)->first();

            if ($data->email_verified_at == null) {
                $data->email_verified_at = now();
                $data->status = Constant::USER_STATUS_VERIFIED;
                $data->save();
            }
        }
    }
}
