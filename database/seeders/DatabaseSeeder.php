<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // User::factory(10)->create();

    //     User::factory()->create([
    //         'name' => 'user',
    //         'email' => 'user@sample.com',
    //         'password' => '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK'
    //     ]);
    // }

    public function run(): void
    {
        $debug = env('APP_DEBUG', false);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
        // テーブルを安全に空にする
        \App\Models\Post::truncate();
        \App\Models\User::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if ($debug) {
            $cnt = 12;
            $this->CreateUsers($cnt);
        }
        // $this->CreateSeeds($debug);
    }


    private function CreateUsers($cnt)
    {
        // DB::unprepared("TRUNCATE TABLE users");
        User::factory($cnt)->create();
        $data = [
            ["admin@sample.jp", "システム管理者", "しすてむかんりしゃ", 999, '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80'],
            ["user1@sample.jp", "ユーザ１", "ゆーざ1", 0, '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK', NULL],
            ["user2@sample.jp", "ユーザ２", "ゆーざ2", 0, '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK', NULL],
            ["user3@sample.jp", "ユーザ３", "ゆーざ3", 0, '$2y$12$UYS4FUuk3JE.CzyF54V7KeFkW9gNi1kzjfFLbj/aemYiTXU3Q0QFK', NULL],
        ];
        $id = 1;
        foreach ($data as $d) {
            $user = User::find($id);
            $user->email = $d[0];
            $user->name = $d[1];
            $user->name_kana = $d[2];
            $user->role = $d[3];
            $user->password = $d[4];
            $user->profile_photo_path = $d[5];
            $user->save();
            $id++;
        }
    }

    private function CreateSeeds($debug)
    {
        $paths = [
            'database/sql/seeds.sql',
        ];
        foreach ($paths as $path) {
            try {
                DB::unprepared(file_get_contents($path));
            } catch (\Exception $e) {
                $messageLines = explode("\n", $e->getMessage());
                echo  $messageLines[0] . "\n";
                throw new \Exception('* SQLで例外が発生した！上のメッセージを見る！');
            }
        }
    }
}
