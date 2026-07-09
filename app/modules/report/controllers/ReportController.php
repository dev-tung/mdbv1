<?php

class ReportController
{
	/**
	 * Revenue Report Page (ONLY render view)
	 */
	public function revenue()
	{
		return View::render('revenue/index');
	}
}
