<?php

namespace App\Website\Controllers;

use App\Core\Env;
use App\Core\View;

class PageController
{
	public function string(): void
	{
		View::render('pages/string');
	}

	public function affiliate(): void
	{
		View::render('pages/affiliate');
	}

	public function career(): void
	{
		View::render('pages/career');
	}

	public function contact(): void
	{
		View::render('pages/contact');
	}

	public function warrantyPolicy(): void
	{
		View::render('pages/warranty-policy');
	}

	public function shippingPolicy(): void
	{
		View::render('pages/shipping-policy');
	}

	public function returnPolicy(): void
	{
		View::render('pages/return-policy');
	}

	public function usedRacket(): void
	{
		$playlistId = 'PLIZyUH46ZTB0';
		$apiKey = Env::get('YOUTUBE_API_KEY');
		$videos = [];
		$videoError = null;

		if (!is_string($apiKey) || trim($apiKey) === '') {
			$videoError = 'Chưa cấu hình YouTube Data API key. Hãy thêm YOUTUBE_API_KEY vào file .env.';
		} else {
			$result = $this->loadPlaylistVideos($playlistId, $apiKey);
			$videos = $result['videos'];
			$videoError = $result['error'];
		}

		View::render('pages/used-racket', compact('videos', 'videoError'));
	}

	private function loadPlaylistVideos(string $playlistId, string $apiKey): array
	{
		$videos = [];
		$pageToken = null;

		do {
			$query = [
				'part' => 'snippet,contentDetails',
				'playlistId' => $playlistId,
				'maxResults' => 50,
				'key' => $apiKey,
			];

			if ($pageToken !== null) {
				$query['pageToken'] = $pageToken;
			}

			$context = stream_context_create([
				'http' => [
					'method' => 'GET',
					'timeout' => 15,
					'ignore_errors' => true,
				],
			]);
			$response = @file_get_contents(
				'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query($query),
				false,
				$context
			);

			if ($response === false) {
				error_log('YouTube playlist request failed before receiving a response.');

				return [
					'videos' => [],
					'error' => 'Không thể tải danh sách video từ YouTube. Vui lòng thử lại sau.',
				];
			}

			$statusCode = 0;
			foreach ($http_response_header ?? [] as $header) {
				if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $matches)) {
					$statusCode = (int) $matches[1];
				}
			}

			$data = json_decode($response, true);

			if ($statusCode < 200 || $statusCode >= 300 || !is_array($data) || isset($data['error'])) {
				$apiMessage = $data['error']['message'] ?? 'Phản hồi không hợp lệ từ YouTube.';
				error_log('YouTube playlist API error (' . $statusCode . '): ' . $apiMessage);
				$errorMessage = $statusCode === 403
					? 'YouTube API đang chặn yêu cầu. Hãy bật YouTube Data API v3 trong Google Cloud và kiểm tra giới hạn API của key có cho phép YouTube Data API v3.'
					: 'YouTube không thể cung cấp danh sách video. Hãy kiểm tra API key và mã playlist.';

				return [
					'videos' => [],
					'error' => $errorMessage,
				];
			}

			foreach ($data['items'] ?? [] as $item) {
				$videoId = $item['contentDetails']['videoId'] ?? '';
				$title = $item['snippet']['title'] ?? '';

				if ($videoId === '' || $title === '' || $title === 'Deleted video' || $title === 'Private video') {
					continue;
				}

				$videos[] = [
					'id' => $videoId,
					'title' => $title,
				];
			}

			$pageToken = $data['nextPageToken'] ?? null;
		} while ($pageToken !== null);

		return [
			'videos' => $videos,
			'error' => null,
		];
	}
}