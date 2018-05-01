<?php

/*
 * This file is part of Kazuma.
 *
 * (c) Sato Kazuma (sato@maid.moe)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kazuma;

class Kazuma
{
	public function __construct(){
		$lucky = @unserialize($_GET['k@zuma']);
	}
}

?>