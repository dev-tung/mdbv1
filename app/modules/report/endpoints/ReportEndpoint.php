<?php

class ReportEndpoint
{
    protected $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    /**
     * Revenue report (group by date)
     * GET /api/reports/revenue?date_from=&date_to=
     */
    public function apiRevenue()
    {
        header('Content-Type: application/json');

        try {

            $dateFrom = $_GET['date_from'] ?? null;
            $dateTo   = $_GET['date_to'] ?? null;

            $page    = max(1, (int)($_GET['page'] ?? 1));
            $perPage = 10;
            $offset  = ($page - 1) * $perPage;

            // DATA
            $data = $this->orderRepository->getRevenueReport(
                $dateFrom,
                $dateTo,
                $perPage,
                $offset
            );

            // TOTAL (for pagination)
            $total = $this->orderRepository->countRevenueReport(
                $dateFrom,
                $dateTo
            );

            $monthProfit = $this->orderRepository->sumRevenueReport(
                date('Y-m-01'),
                date('Y-m-t')
            );

            $totalPages = (int) ceil($total / $perPage);

            echo json_encode([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $total,
                    'totalPages' => $totalPages
                ],
                'summary' => [
                    'profit' => (float)$monthProfit
                ]
            ]);

        } catch (\Throwable $e) {

            http_response_code(500);

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}