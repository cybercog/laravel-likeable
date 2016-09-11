<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;

class RenameLikeCounterTable extends Migration
{
    public function up()
    {
        Schema::rename('like_counter', 'likers_counter');
    }

    public function down()
    {
        Schema::drop('liker_counter');
    }
}
