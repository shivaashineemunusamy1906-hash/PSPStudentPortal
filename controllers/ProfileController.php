<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/User.php";

class ProfileController
{
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();

        $this->userModel = new User($db);
    }

    public function getProfile($userId)
    {
        return $this->userModel->getUserById($userId);
    }

    public function changePassword($userId, $newPassword)
    {
        return $this->userModel->updatePassword(
            $userId,
            $newPassword
        );
    }

    public function uploadProfilePicture($userId, $file)
    {
        // Check if a file was selected
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return [
                'success' => false,
                'message' => 'Please select a profile picture.'
            ];
        }

        // Check upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'message' => 'File upload failed.'
            ];
        }

        // Maximum file size: 2MB
        $maxSize = 2 * 1024 * 1024;

        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'message' => 'File size must not exceed 2MB.'
            ];
        }

        // Allowed file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        $extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowedExtensions, true)) {
            return [
                'success' => false,
                'message' => 'Only JPG, JPEG and PNG files are allowed.'
            ];
        }

        // Check actual file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $mimeType = finfo_file(
            $finfo,
            $file['tmp_name']
        );

        finfo_close($finfo);

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png'
        ];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            return [
                'success' => false,
                'message' => 'Invalid image file.'
            ];
        }

        // Create a unique filename
        $newFileName = uniqid('profile_', true) . '.' . $extension;

        // Upload folder
        $uploadDirectory = __DIR__ . '/../uploads/';

        // Create folder if it does not exist
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        // Full destination
        $destination = $uploadDirectory . $newFileName;

        // Move uploaded file
        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            return [
                'success' => false,
                'message' => 'Unable to save uploaded file.'
            ];
        }

        // Save filename into database
        $updated = $this->userModel->updateProfilePicture(
            $userId,
            $newFileName
        );

        if (!$updated) {

            if (file_exists($destination)) {
                unlink($destination);
            }

            return [
                'success' => false,
                'message' => 'Unable to update database.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Profile picture uploaded successfully.'
        ];
    }
}

?>