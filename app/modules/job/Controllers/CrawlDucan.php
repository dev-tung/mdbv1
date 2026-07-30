<?php

namespace App\Job\Controllers;

use App\Core\Database;

class CrawlDucan
{
	protected string $baseUrl = 'https://ducansport.vn/vot-cau-long-yonex';

	public function run(): void
	{
		dd($this->baseUrl);
	}

}
