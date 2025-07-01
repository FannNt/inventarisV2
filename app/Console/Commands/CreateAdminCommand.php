<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate superadmin account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $input['name'] = $this->ask('Enter Username?');
        $input['email'] = $this->ask('Enter Email?');
        $input['password'] = $this->secret('Enter Password?');
        $input['password_confirmation'] = $this->secret('Confirm Password?');
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()){
            $this->error("Validation error:". implode(", ",
                    $validator->errors()->all()));
            return;
        }
        $input['is_admin'] = true;
        unset($input['password_confirmation']);
        try {
            $user = User::create($input);
            $user->assignRole('superadmin');
            $this->info('Superadmin account created successfully!');
        }catch (\Exception $exception){
            $this->error('Got error: ' . $exception->getMessage());
        }

    }
}
