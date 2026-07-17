<?php
function upload_file(array $file, string $directory): ?string
{
	if ($file['error'] !== UPLOAD_ERR_OK) {
		return null;
	}

	if (!is_dir($directory)) {
		mkdir($directory, 0777, true);
	}

	$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

	$filename = uniqid() . '.' . $extension;

	move_uploaded_file($file['tmp_name'], $directory . '/' . $filename);

	return $filename;
}
