<?php

namespace App\Repositories;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Juku;
use App\Models\User;
use App\Models\UserGroup;

class PostRepository extends BaseRepository
{

    static function gets(): array
    {
        $sql = <<< EOS
SELECT
    p.*,
    u.profile_photo_path, u.name AS user_name, u.role,
    GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS category_names
FROM posts AS p
INNER JOIN category_posts AS cap ON cap.post_id = p.id
INNER JOIN categories AS c ON c.id = cap.category_id
INNER JOIN users AS u ON u.id = p.user_id
GROUP BY p.id
EOS;
        return DB::select($sql, []);
    }

	static function findPost($id)
	{
		// $user = [];
        $sql = <<< EOS
SELECT
	p.*,
	u.profile_photo_path,
	u.NAME AS user_name,
	u.role,
	GROUP_CONCAT( c.NAME ORDER BY c.NAME SEPARATOR ', ' ) AS category_names 
FROM
	posts AS p
	INNER JOIN category_posts AS cap ON cap.post_id = p.id
	INNER JOIN categories AS c ON c.id = cap.category_id
	INNER JOIN users AS u ON u.id = p.user_id 
WHERE
	p.id = ?
GROUP BY
	p.id
EOS;
        $results = DB::select($sql, [$id]);
		return $results[0];
	}
}