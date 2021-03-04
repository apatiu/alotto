<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jet:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $user;
    private $creator;

    public function __construct(User $user, CreateNewUser $creator)
    {
        parent::__construct();

        $this->user = $user;
        $this->creator = $creator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $details = $this->getDetails();
//        $details = [
//            'name' => 'Admin',
//            'username' => 'admin',
//            'email' => 'admin@app.com',
//            'password' => 'password',
//            'password_confirmation' => 'password',
//            'terms' => ''
//        ];
//        $admin = $this->user->create($details);
//        dd($details);
        $user = $this->creator->create($details);
        $this->display($user);
    }

    private function getDetails(): array
    {
        $details['name'] = $this->ask('Name');
        $details['username'] = $this->ask('Username');
        $details['email'] = $this->ask('Email');
        $details['password'] = $this->secret('Password');
        $details['password_confirmation'] = $this->secret('Confirm password');

        while (!$this->isValidPassword($details['password'], $details['password_confirmation'])) {
            if (!$this->isRequiredLength($details['password'])) {
                $this->error('Password must be more that six characters');
            }

            if (!$this->isMatch($details['password'], $details['password_confirmation'])) {
                $this->error('Password and Confirm password do not match');
            }

            $details['password'] = $this->secret('Password');
            $details['password_confirmation'] = $this->secret('Confirm password');
        }

        return $details;
    }

    private function display(User $admin): void
    {
        $headers = ['Name', 'Username', 'Email'];

        $fields = [
            'Name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email
        ];

        $this->info('User created');
        $this->table($headers, [$fields]);
    }

    private function isValidPassword(string $password, string $confirmPassword): bool
    {
        return $this->isRequiredLength($password) &&
            $this->isMatch($password, $confirmPassword);
    }

    /**
     * Check if password and confirm password matches.
     *
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    private function isMatch(string $password, string $confirmPassword): bool
    {
        return $password === $confirmPassword;
    }

    /**
     * Checks if password is longer than six characters.
     *
     * @param string $password
     * @return bool
     */
    private function isRequiredLength(string $password): bool
    {
        return strlen($password) >= 6;
    }

}
