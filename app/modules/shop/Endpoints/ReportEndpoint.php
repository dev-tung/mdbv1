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
		$filters = request_filters([
			'keyword',
			'product_id',
			'purchase_id',
		]);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getInventory($filters),
		]);
	}

	public function apiRevenue()
	{
		$filters = request_filters(['keyword']);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getRevenue($filters),
		]);
	}
	
	public function apiCustomer()
	{
		$filters = request_filters(['keyword']);

		return Response::json([
			'success' => true,
			'data' => $this->reportRepository->getCustomer($filters),
		]);
	}
}
