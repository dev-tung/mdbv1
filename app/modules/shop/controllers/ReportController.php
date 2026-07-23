<?php

class ReportController
{
	public function inventory(): void
	{
		View::render('report/inventory');
	}

	public function revenue(): void
	{
		View::render('report/revenue');
	}

	public function customer(): void
	{
		View::render('report/customer');
	}
}
