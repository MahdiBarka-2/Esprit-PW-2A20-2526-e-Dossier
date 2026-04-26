<?php
/**
 * User Model - Data Structure
 * This class defines the properties for a User, acting as a data container.
 * Implementation logic for these properties is handled in CONTROLLER/UserController.php.
 */
class User
{
    public $id;
    public $name;
    public $email;
    public $password_hash;
    public $password_plain;
    public $role;
    public $profile_image_url;
    public $cv_file_path;
    public $phone;
    public $status;
    public $created_at;
    public $updated_at;
}
?>