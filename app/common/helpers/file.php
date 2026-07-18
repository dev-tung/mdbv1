<?php

/* =================================================
   EXISTS
================================================= */

function file_exists_path(string $path): bool
{
	return is_file($path);
}

/* =================================================
   DIRECTORY
================================================= */

function create_directory(string $path): void
{
	if (!is_dir($path)) {
		mkdir($path, 0777, true);
	}
}

/* =================================================
   EXTENSION
================================================= */

function file_extension(string $filename): string
{
	return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/* =================================================
   FILENAME
================================================= */

function generate_filename(string $filename): string
{
	return uniqid('', true) . '.' . file_extension($filename);
}

/* =================================================
   MIME
================================================= */

function file_mime(array $file): string
{
	return mime_content_type($file['tmp_name']);
}

/* =================================================
   SIZE
================================================= */

function file_size(array $file): int
{
	return (int) $file['size'];
}

/* =================================================
   IMAGE
================================================= */

function is_image(array $file): bool
{
	$mime = file_mime($file);

	return str_starts_with($mime, 'image/');
}

/* =================================================
   VALID IMAGE
================================================= */

function validate_image(
	array $file,
	int $maxSize = 5 * 1024 * 1024,
	array $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'],
): ?string {
	if (empty($file['name'])) {
		return 'Vui lòng chọn ảnh.';
	}

	if ($file['error'] !== UPLOAD_ERR_OK) {
		return 'Upload thất bại.';
	}

	if (!is_image($file)) {
		return 'File không phải ảnh.';
	}

	if (!in_array(file_extension($file['name']), $extensions)) {
		return 'Định dạng không hợp lệ.';
	}

	if (file_size($file) > $maxSize) {
		return 'Ảnh quá lớn.';
	}

	return null;
}

/* =================================================
   UPLOAD
================================================= */

function upload_file(array $file, string $directory): ?string
{
	if (empty($file['name'])) {
		return null;
	}

	create_directory($directory);

	$filename = generate_filename($file['name']);

	$destination = $directory . '/' . $filename;

	if (!move_uploaded_file($file['tmp_name'], $destination)) {
		return null;
	}

	return $filename;
}

/* =================================================
   DELETE
================================================= */

function delete_file(string $directory, ?string $filename): bool
{
	if (empty($filename)) {
		return false;
	}

	$path = $directory . '/' . $filename;

	if (!file_exists_path($path)) {
		return false;
	}

	return unlink($path);
}

/* =================================================
   REPLACE
================================================= */

function replace_file(array $file, string $directory, ?string $oldFile = null): ?string
{
	$newFile = upload_file($file, $directory);

	if (!$newFile) {
		return $oldFile;
	}

	delete_file($directory, $oldFile);

	return $newFile;
}

/* =================================================
   URL
================================================= */

function file_url(string $directory, ?string $filename, string $default = ''): string
{
	if (empty($filename)) {
		return $default;
	}

	return rtrim($directory, '/') . '/' . $filename;
}
