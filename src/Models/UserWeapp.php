<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Aiwhj\WeappLogin\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\User;

class UserWeapp extends Model {
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = true;
	protected $table = 'user_weapp';

	/**
	 * Get user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 * @author Seven Du <shiweidu@outlook.com>
	 */
	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	/**
	 * Set type.
	 *
	 * @param string $type
	 * @author Seven Du <shiweidu@outlook.com>
	 */

	public function provider(int $user_id) {
		return $this->where('user_id', $user_id);
	}

}
