<?php
namespace App\Modules\Shop\Endpoints;
use App\Core\Response;
use App\Modules\Shop\Repositories\ReportRepository;

class ReportEndpoint
{
	protected ReportRepository $reportRepository;

	public function __construct()
	{
		$this->reportRepository = new ReportRepository();
	}

	public function apiListInventory()
	{
		$filters = request_filters(['keyword']);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getInventory($filters),
		]);
	}

	public function apiListRevenue()
	{
		$filters = request_filters(['keyword']);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getRevenue($filters),
		]);
	}
	
	public function apiListCustomer()
	{
		$filters = request_filters(['keyword']);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getCustomer($filters),
		]);
	}
}
