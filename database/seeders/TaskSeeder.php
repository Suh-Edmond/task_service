<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{

    private $users;

    public function __construct()
    {
        $this->users = User::all()->pluck('id');
    }

    /**
     * Run the database seeds.
     */
    public function run(Generator $generator): void
    {
        for ($i = 0; $i < 20; $i++) {
            Task::create([
                'title' => $generator->title,
                'description' => $generator->sentence,
                'status' => $generator->randomElement([true, false]),
                'due_date' => $generator->date,
                'user_id' => $generator->randomElement($this->users)
            ]);
        }
    }
}
