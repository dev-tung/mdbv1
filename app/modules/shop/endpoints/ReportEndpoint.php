<?php

class ReportEndpoint
{
	protected ReportRepository $reportRepository;

	public function __construct()
	{
		$this->reportRepository = new ReportRepository();
	}

	public function apiInventory()
	{
		$filters = request_filters(['keyword']);

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
