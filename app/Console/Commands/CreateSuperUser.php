<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:superadmin {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('=== Create Super Admin User ===');
        try {
            DB::beginTransaction();
            $name = !empty($this->option('name')) ? $this->option('name') : 'Super Admin';
            $email = !empty($this->option('email')) ? $this->option('email') : 'superadmin@gmail.com';
            $password = !empty($this->option('password')) ? $this->option('password') : '12345678';

            $this->line('Name: '.$name);
            $this->line('Email: '.$email);
            $this->line('Password: '.$password);

            $user = \App\Models\User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole('superadmin');

            DB::commit();
            $this->newLine();
            $this->info('Info: Super Admin User Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->newLine();
            $this->error('Error: Super Admin User Created Failed');
            $this->error($th->getMessage());
        }
    }
}
