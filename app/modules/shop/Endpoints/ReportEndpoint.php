<?php

namespace App\Shop\Endpoints;

use App\Core\Response;
use App\Shop\Repositories\ReportRepository;

class ReportEndpoint
{
	protected ReportRepository $reportRepository;

	public function __construct()
	{
		$this->reportRepository = new ReportRepository();
	}

	public function apiInventory()
	{
		$filters = request_all();

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getInventory($filters),
		]);
	}

	public function apiRevenue()
	{
			$filters = request_all();

			return Response::json([
					'success' => true,
					'data' => $this->reportRepository->getRevenue($filters),
			]);
	}

	public function apiBuyer()
	{
		$filters = request_all();

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getBuyer($filters),
		]);
	}
}
