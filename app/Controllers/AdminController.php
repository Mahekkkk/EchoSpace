<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CiAuth;
use App\Models\User;
use App\Libraries\hash;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\Category;
use SSP;
use SawaStacks\CodeIgniter\Slugify;
use App\Models\SubCategory;
use App\Models\Post;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserPost;


class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'text'];
    protected $db;

    public function __construct()
    {
        require_once APPPATH . 'ThirdParty\ssp.php';
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('backend/pages/home.php', $data);
    }

    public function logoutHandler()
    {
        CiAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'You are logged out!');
    }

    public function profile()
    {
        $data = [
            'pageTitle' => 'Profile'
        ];
        return view('backend/pages/profile.php', $data);
    }

    public function updatePersonalDetails()
    {
        log_message('info', 'updatePersonalDetails called');

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Not an AJAX request']);
        }

        $validation = \Config\Services::validation();
        $user_id = CiAuth::id();

        $rules = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Full name is required',
                ],
            ],
            'username' => [
                'rules' => 'required|min_length[5]|is_unique[users.username,id,' . $user_id . ']',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must have a minimum of 5 characters',
                    'is_unique' => 'Username is already taken!',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            return $this->response->setJSON(['status' => 0, 'error' => $errors]);
        }

        $userModel = new User();
        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'bio' => $this->request->getPost('bio'),
        ];

        if ($userModel->update($user_id, $data)) {
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Profile updated successfully',
                'user_info' => $data
            ]);
        }

        return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to update profile']);
    }

    public function updateProfilePicture()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Not an AJAX request']);
        }

        $user_id = CiAuth::id(); // Get the authenticated user's ID
        $user = new User(); // User model
        $user_info = $user->asObject()->where('id', $user_id)->first(); // Fetch user information
        $path = 'images/users/'; // Path to store profile pictures
        $file = $this->request->getFile('user_profile_file'); // Get the uploaded file

        if (!$file->isValid()) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Invalid file upload']);
        }

        $new_filename = "UIMG_" . $user_id . '_' . $file->getRandomName(); // Generate a unique filename
        $old_picture = $user_info->picture; // Retrieve the old profile picture filename

        try {
            // Resize and save the image
            \Config\Services::image()
                ->withFile($file) // Load the file
                ->resize(500, 500, true, 'height') // Resize to 450x450 while maintaining aspect ratio
                ->save($path . $new_filename); // Save the resized image with the new filename

            // Update the user's profile picture in the database
            $user->update($user_id, ['picture' => $new_filename]);

            // Delete the old picture if it exists and is not the default avatar
            if ($old_picture && $old_picture !== 'default_avatar1.gif' && file_exists($path . $old_picture)) {
                unlink($path . $old_picture);
            }

            // Return success response
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Profile picture updated successfully',
                'new_picture' => $new_filename
            ]);
        } catch (\Exception $e) {
            // Return error response in case of an exception
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to process and upload the profile picture. ' . $e->getMessage()
            ]);
        }
    }


    public function removeProfilePicture()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Not an AJAX request']);
        }

        $user_id = CiAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();
        $path = 'images/users/';
        $old_picture = $user_info->picture;

        if ($old_picture && $old_picture != 'default_avatar1.gif') {
            if (file_exists($path . $old_picture)) {
                unlink($path . $old_picture);
            }
        }

        $user->update($user_id, ['picture' => 'default_avatar1.gif']);

        return $this->response->setJSON(['status' => 1, 'msg' => 'Profile picture removed successfully']);
    }
    public function changePassword()
    {
        $request = \Config\Services::request();

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Invalid request']);
        }

        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();

        if (!$user_info) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'User not found']);
        }

        // Validation rules
        $rules = [
            'current_password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Enter current password',
                    'min_length' => 'Password must have at least 5 characters',
                ]
            ],
            'new_password' => [
                'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'Enter a new password',
                    'min_length' => 'New password must have at least 5 characters',
                    'max_length' => 'New password must have at most 20 characters',
                    'is_password_strong' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
                ]
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Confirm your new password',
                    'matches' => 'The confirmation password does not match the new password'
                ]
            ]
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            $errors = $validation->getErrors();
            return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
        }

        // Check current password
        if (!password_verify($this->request->getPost('current_password'), $user_info->password)) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'The current password is incorrect']);
        }

        // Update user password in DB
        $new_password_hash = password_hash($request->getVar('new_password'), PASSWORD_DEFAULT);

        try {
            $user->where('id', $user_info->id)
                ->set(['password' => $new_password_hash])
                ->update();

            // Send email notification to the user
            $mail_data = [
                'user' => $user_info,
                'new_password' => $request->getVar('new_password')
            ];

            $view = \Config\Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)
                ->render('email-templates/password-changed-email-template.php');

            $mailConfig = [
                'mail_from_email' => env('EMAIL_DEFAULT_FROM_EMAIL'),
                'mail_from_name' => env('EMAIL_DEFAULT_FROM_NAME'),
                'mail_recipient_email' => $user_info->email,
                'mail_recipient_name' => $user_info->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            ];

            sendEmail($mailConfig);

            return $this->response->setJSON([
                'status' => 1,
                'token' => csrf_hash(),
                'msg' => 'Password change hogya tumhara ...Yeahh'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to update password. Please try again later.']);
        }
    }
    public function settings()
    {
        $data = [
            'pageTitle' => 'Settings',
        ];

        return view("backend/pages/settings", $data);
    }
    public function updateGeneralSettings()
    {
        $request = \Config\Services::request();

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 0, 'error' => 'Invalid request']);
        }

        $validation = \Config\Services::validation();

        // Validation rules
        $rules = [
            'blog_title' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Blog title is required',
                ]
            ],
            'blog_email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Blog email is required',
                    'valid_email' => 'Invalid email address',
                ]
            ],
            'blog_phone' => [
                'rules' => 'required|numeric|exact_length[10]',
                'errors' => [
                    'required' => 'Blog phone number is required',
                    'numeric' => 'Blog phone number must contain only digits',
                    'exact_length' => 'Blog phone number must be exactly 10 digits',
                ]
            ]
        ];


        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            // If validation fails, return errors
            $errors = $validation->getErrors();
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'error' => $errors
            ]);
        }

        try {
            // Load the Setting model
            $settingModel = new Setting();

            // Get form data
            $data = [
                'blog_title' => $this->request->getVar('blog_title'),
                'blog_email' => $this->request->getVar('blog_email'),
                'blog_phone' => $this->request->getVar('blog_phone'),
                'blog_meta_keywords' => $this->request->getVar('blog_meta_keywords'),
                'blog_meta_description' => $this->request->getVar('blog_meta_description')
            ];

            // Update settings in the database
            if ($settingModel->update(1, $data)) { // Assuming `id = 1` for a single row settings table
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Settings updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Failed to update settings'
                ]);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }


    public function updateBlogLogo()
    {
        $settings = new Setting();
        $path = 'images/blog/';

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Invalid request type. Only AJAX is allowed.'
            ]);
        }

        $file = $this->request->getFile('blog_logo');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Invalid file upload. Please try again.'
            ]);
        }

        $setting_data = $settings->asObject()->first();
        $old_blog_logo = $setting_data->blog_logo ?? null;
        $new_filename = 'EchoSpace_logo_' . $file->getRandomName();

        if ($file->move($path, $new_filename)) {
            // Delete old logo file
            if ($old_blog_logo && file_exists($path . $old_blog_logo)) {
                unlink($path . $old_blog_logo);
            }

            // Update database
            $update = $settings->where('id', $setting_data->id)
                ->set(['blog_logo' => $new_filename])
                ->update();

            if ($update) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Blog logo updated successfully.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 0,
            'token' => csrf_hash(),
            'msg' => 'Failed to update blog logo. Please try again later.'
        ]);
    }

    public function updateBlogFavicon()
    {
        $settings = new Setting();
        $path = 'images/blog/';

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Invalid request type. Only AJAX is allowed.'
            ]);
        }

        $file = $this->request->getFile('blog_favicon');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON([
                'status' => 0,
                'token' => csrf_hash(),
                'msg' => 'Invalid file upload. Please try again.'
            ]);
        }

        $setting_data = $settings->asObject()->first();
        $old_favicon = $setting_data->blog_favicon ?? null;
        $new_filename = 'EchoSpace_favicon_' . $file->getRandomName();

        if ($file->move($path, $new_filename)) {
            // Delete old favicon file
            if ($old_favicon && file_exists($path . $old_favicon)) {
                unlink($path . $old_favicon);
            }

            // Update database
            $update = $settings->where('id', $setting_data->id)
                ->set(['blog_favicon' => $new_filename])
                ->update();

            if ($update) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Blog favicon updated successfully.'
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 0,
            'token' => csrf_hash(),
            'msg' => 'Failed to update blog favicon. Please try again later.'
        ]);
    }

    public function updateSocialMedia()
    {
        $socialMediaModel = new \App\Models\SocialMedia(); // Load the SocialMedia model

        // Validate incoming request
        $validationRules = [
            'facebook_url'   => 'permit_empty|valid_url',
            'twitter_url'    => 'permit_empty|valid_url',
            'instagram_url'  => 'permit_empty|valid_url',
            'youtube_url'    => 'permit_empty|valid_url',
            'github_url'     => 'permit_empty|valid_url',
            'linkedin_url'   => 'permit_empty|valid_url',
        ];

        if (!$this->validate($validationRules)) {
            // Return validation errors
            return $this->response->setJSON([
                'status' => 0,
                'token'  => csrf_hash(),
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Fetch data from request
        $data = [
            'facebook_url'   => $this->request->getPost('facebook_url'),
            'twitter_url'    => $this->request->getPost('twitter_url'),
            'instagram_url'  => $this->request->getPost('instagram_url'),
            'youtube_url'    => $this->request->getPost('youtube_url'),
            'github_url'     => $this->request->getPost('github_url'),
            'linkedin_url'   => $this->request->getPost('linkedin_url'),
        ];

        // Check if a record exists, otherwise create a new one
        $existingSocialMedia = $socialMediaModel->first();

        if ($existingSocialMedia) {
            // Update the existing record
            $update = $socialMediaModel->update($existingSocialMedia['id'], $data);
        } else {
            // Insert a new record
            $update = $socialMediaModel->insert($data);
        }

        if ($update) {
            return $this->response->setJSON([
                'status' => 1,
                'token'  => csrf_hash(),
                'msg'    => 'Social media settings updated successfully!',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'token'  => csrf_hash(),
                'msg'    => 'Failed to update social media settings. Please try again later.',
            ]);
        }
    }

    public function categories()
    {
        $data = [
            'pageTitle' => 'Categories',
        ];
        return view('backend/pages/categories/categories.php', $data);
    }

    public function addCategory()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();

            $rules = [
                'category_name' => [
                    'rules'  => 'required|is_unique[categories.name]',
                    'errors' => [
                        'required'  => 'Category name is required.',
                        'is_unique' => 'Category name already exists.',
                    ],
                ],
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg'    => 'Validation errors occurred.',
                    'token'  => csrf_hash(), // Update CSRF token
                    'error'  => $validation->getErrors(),
                ]);
            }

            $categoryModel = new \App\Models\Category();
            $data = [
                'name'     => $this->request->getPost('category_name'),
                'ordering' => $this->request->getPost('ordering') ?? 10000,
            ];

            if ($categoryModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg'    => 'Category added successfully.',
                    'token'  => csrf_hash(),
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg'    => 'Failed to save category. Please try again.',
                    'token'  => csrf_hash(),
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg'    => 'Invalid request type.',
                'token'  => csrf_hash(),
            ]);
        }
    }

    public function getCategories()
    {
        // Database details for DataTables
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db"   => $this->db->database,
        );

        // Table name
        $table = "categories";

        // Primary key
        $primaryKey = "id";

        // Columns to be read and sent to DataTables
        $columns = array(
            array(
                "db" => "id",
                "dt" => 0
            ),
            array(
                "db" => "name",
                "dt" => 1
            ),
            array(
                "db" => "id",
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    // Example of formatting data
                    $db = \Config\Database::connect();
                    $subCategoryModel = $db->table('sub_categories');
                    $subCategoryCount = $subCategoryModel->where('parent_cat', $row['id'])->countAllResults();

                    return $subCategoryCount . "";
                }
            ),
            array(
                "db" => "id",
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    return "<div class='btn-group'>
                            <button class='btn btn-sm btn-link p-0 mx-1 editCategoryBtn' data-id='" . $row['id'] . "'>
                                Edit
                            </button>
                            <button class='btn btn-sm btn-link p-0 mx-1 deleteCategoryBtn' data-id='" . $row['id'] . "'>
                                Delete
                            </button>
                        </div>";
                },
            ),
            array(
                "db" => "ordering",
                "dt" => 4,
            ),

        );



        echo json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function getCategory()
    {
        $request = \Config\Services::request(); // Get the request instance

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id'); // Get category_id from the request
            $categoryModel = new Category(); // Load the Category model

            $category_data = $categoryModel->find($id); // Fetch the category by ID
            // return $this->response->setJSON(['data' => $category_data]);

            if ($category_data) {
                return $this->response->setJSON([
                    'status' => 1,
                    'data'   => $category_data,
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg'    => 'Category not found.',
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg'    => 'Invalid request type.',
            ]);
        }
    }

    public function updateCategory()
    {
        $request = \Config\Services::request(); // Get the request instance

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id'); // Get the category ID from the request
            $categoryName = $request->getVar('category_name');

            $validation = \Config\Services::validation(); // Get the validation service

            // Validation rules
            $validation->setRules([
                'category_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Category name is required.',
                    ],
                ],
            ]);

            // Check validation
            if (!$validation->run(['category_name' => $categoryName])) {
                // Validation failed
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(), // Update CSRF token
                    'error' => $errors,
                ]);
            }

            // Check uniqueness manually
            $category = new Category();
            $exists = $category->where('name', $categoryName)->where('id !=', $id)->first();
            if ($exists) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => ['category_name' => 'Category name already exists.'],
                ]);
            }

            // Update the category
            $update = $category->update($id, ['name' => $categoryName]);
            if ($update) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Category updated successfully.',
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Category update failed.',
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setJSON(['msg' => 'Invalid request']);
    }

    // public function deleteCategory()
    // {
    //     $request = \Config\Services::request();

    //     if ($request->isAJAX()) {
    //         $id = $request->getVar('category_id');
    //         $category = new Category(); // Use your model

    //         if ($category->delete($id)) {
    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'token' => csrf_hash(), // Update CSRF token
    //                 'msg' => 'Category deleted successfully.',
    //             ]);
    //         } else {
    //             return $this->response->setJSON([
    //                 'status' => 0,
    //                 'token' => csrf_hash(), // Update CSRF token
    //                 'msg' => 'Failed to delete category.',
    //             ]);
    //         }
    //     }

    //     return $this->response->setStatusCode(400)->setJSON(['msg' => 'Invalid request']);
    // }

    public function deleteCategory()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $id = $request->getVar('category_id'); // Get the category ID
            $category = new Category(); // Category model
            $subcategory = new SubCategory(); // SubCategory model

            // Check for related subcategories
            $subcategories = $subcategory->where('parent_cat', $id)->findAll();
            if ($subcategories) {
                $count = count($subcategories);
                $msg = $count === 1
                    ? "There is ($count) subcategory related to this parent category, so it cannot be deleted."
                    : "There are ($count) subcategories related to this parent category, so it cannot be deleted.";

                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(), // Update CSRF token
                    'msg' => $msg,
                ]);
            }

            // Proceed with category deletion
            if ($category->delete($id)) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(), // Update CSRF token
                    'msg' => 'Category deleted successfully.',
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(), // Update CSRF token
                    'msg' => 'Failed to delete category.',
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 0,
            'msg' => 'Invalid request',
            'token' => csrf_hash(), // Update CSRF token
        ]);
    }


    public function reorderCategories()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            // Retrieve positions array from the request
            $positions = $request->getVar('positions');
            $categoryModel = new Category();

            if ($positions && is_array($positions)) {
                foreach ($positions as $position) {
                    $id = $position['id'];
                    $newPosition = $position['ordering'];

                    // Update the ordering for each category
                    $categoryModel->update($id, ['ordering' => $newPosition]);
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Categories reordered successfully.',
                    'token' => csrf_hash() // Return the refreshed CSRF token
                ]);
            }

            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid data format.',
                'token' => csrf_hash()
            ]);
        }

        // If not an AJAX request, return an error
        return $this->response->setStatusCode(400)->setJSON([
            'msg' => 'Invalid request.',
            'token' => csrf_hash()
        ]);
    }
    public function getParentCategories()
    {
        if ($this->request->isAJAX()) {
            // Load the Category model
            $categoryModel = new Category();

            try {
                // Fetch all parent categories
                $parent_categories = $categoryModel->findAll();

                // Default option
                $options = '<option value="0">Uncategorized</option>';

                if ($parent_categories) {
                    foreach ($parent_categories as $parent_category) {
                        $options .= '<option value="' . $parent_category['id'] . '">' . esc($parent_category['name']) . '</option>';
                    }
                }

                return $this->response->setJSON([
                    'status' => 1,
                    'data' => $options,
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Error fetching categories: ' . $e->getMessage());

                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Failed to fetch parent categories. Please try again.',
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 0,
            'msg' => 'Invalid request.',
        ]);
    }

    // public function addSubCategory()
    // {
    //     if ($this->request->isAJAX()) {
    //         $validation = \Config\Services::validation();

    //         // Set validation rules
    //         $validation->setRules([
    //             'subcategory_name' => [
    //                 'rules' => 'required|is_unique[sub_categories.name]',
    //                 'errors' => [
    //                     'required' => 'Subcategory name is required.',
    //                     'is_unique' => 'Subcategory name already exists.',
    //                 ],
    //             ],
    //             'description' => [
    //                 'rules' => 'permit_empty|max_length[1000]',
    //                 'errors' => [
    //                     'max_length' => 'Description cannot exceed 1000 characters.',
    //                 ],
    //             ],
    //             'parent_cat' => [
    //                 'rules' => 'required|integer',
    //                 'errors' => [
    //                     'required' => 'Parent category is required.',
    //                     'integer' => 'Invalid parent category.',
    //                 ],
    //             ],
    //         ]);

    //         if (!$validation->withRequest($this->request)->run()) {
    //             return $this->response->setJSON([
    //                 'status' => 0,
    //                 'error' => $validation->getErrors(),
    //                 'token' => csrf_hash(),
    //             ]);
    //         }

    //         $subcategory = new \App\Models\SubCategory();

    //         $subcategory_name = $this->request->getVar('subcategory_name');
    //         $subcategory_description = $this->request->getVar('description');
    //         $subcategory_parent_category = $this->request->getVar('parent_cat');

    //         // Generate slug using Slugify
    //         $subcategory_slug = \SawaStacks\CodeIgniter\Slugify::model(\App\Models\SubCategory::class)->make($subcategory_name);

    //         // Save the subcategory
    //         $save = $subcategory->save([
    //             'name' => $subcategory_name,
    //             'parent_cat' => $subcategory_parent_category,
    //             'slug' => $subcategory_slug,
    //             'description' => $subcategory_description,
    //         ]);

    //         if ($save) {
    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'msg' => 'Subcategory added successfully.',
    //                 'token' => csrf_hash(),
    //             ]);
    //         }

    //         return $this->response->setJSON([
    //             'status' => 0,
    //             'msg' => 'Failed to add subcategory. Please try again.',
    //             'token' => csrf_hash(),
    //         ]);
    //     }

    //     return $this->response->setStatusCode(400)->setJSON([
    //         'status' => 0,
    //         'msg' => 'Invalid request.',
    //         'token' => csrf_hash(),
    //     ]);
    // }

    public function addSubCategory()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();

            // Set validation rules
            $validation->setRules([
                'subcategory_name' => [
                    'rules' => 'required|is_unique[sub_categories.name]',
                    'errors' => [
                        'required' => 'Subcategory name is required.',
                        'is_unique' => 'Subcategory name already exists.',
                    ],
                ],
                'description' => [
                    'rules' => 'permit_empty|max_length[1000]',
                    'errors' => [
                        'max_length' => 'Description cannot exceed 1000 characters.',
                    ],
                ],
                'parent_cat' => [
                    'rules' => 'required|integer',
                    'errors' => [
                        'required' => 'Parent category is required.',
                        'integer' => 'Invalid parent category.',
                    ],
                ],
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status' => 0,
                    'error' => $validation->getErrors(),
                    'token' => csrf_hash(),
                ]);
            }

            $subcategory = new \App\Models\SubCategory();

            $subcategory_name = $this->request->getVar('subcategory_name');
            $subcategory_description = $this->request->getVar('description');
            $subcategory_parent_category = $this->request->getVar('parent_cat');

            // Generate slug using Slugify
            $subcategory_slug = \SawaStacks\CodeIgniter\Slugify::model(\App\Models\SubCategory::class)->make($subcategory_name);

            // Determine the next ordering value
            $highestOrder = $subcategory->selectMax('ordering')->first();
            $nextOrder = ($highestOrder['ordering'] ?? 0) + 1;

            // Save the subcategory
            $save = $subcategory->save([
                'name' => $subcategory_name,
                'parent_cat' => $subcategory_parent_category,
                'slug' => $subcategory_slug,
                'description' => $subcategory_description,
                'ordering' => $nextOrder, // Set the calculated ordering value
            ]);

            if ($save) {
                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Subcategory added successfully.',
                    'token' => csrf_hash(),
                ]);
            }

            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to add subcategory. Please try again.',
                'token' => csrf_hash(),
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 0,
            'msg' => 'Invalid request.',
            'token' => csrf_hash(),
        ]);
    }



    // public function getSubCategories()
    // {
    //     if ($this->request->isAJAX()) {
    //         try {
    //             $subcategoryModel = new SubCategory();

    //             // Fetch subcategories and join with parent category names
    //             $subcategories = $subcategoryModel
    //                 ->select('sub_categories.id, sub_categories.name, sub_categories.ordering, sub_categories.description, categories.name as parent_category')
    //                 ->join('categories', 'categories.id = sub_categories.parent_cat', 'left') // Left join for parent categories
    //                 ->findAll();

    //             $data = [];
    //             foreach ($subcategories as $subcategory) {
    //                 // $posts = $post->where(['category_id' => $subcategory['id']])->findAll();
    //                 // $postsCount = count($posts);
    //                 $post = new Post();
    //                 $postsCount = $post->where(['category_id' => $subcategory['id']])->countAllResults();

    //                 $data[] = [
    //                     'id' => $subcategory['id'],
    //                     'name' => $subcategory['name'],
    //                     'parent_category' => $subcategory['parent_category'] ?? 'Uncategorized',
    //                     'posts_count' => $postsCount, // Placeholder value
    //                     'action' => "
    //                     <div class='btn-group'>
    //                         <button class='btn btn-sm btn-primary editSubCategoryBtn' data-id='{$subcategory['id']}'>Edit</button>
    //                         <button class='btn btn-sm btn-danger deleteSubCategoryBtn' data-id='{$subcategory['id']}'>Delete</button>
    //                     </div>",
    //                     'ordering' => $subcategory['ordering'],
    //                 ];
    //             }

    //             return $this->response->setJSON(['data' => $data]);
    //         } catch (\Exception $e) {
    //             log_message('error', 'Error fetching subcategories: ' . $e->getMessage());
    //             return $this->response->setJSON([
    //                 'status' => 0,
    //                 'msg' => 'Failed to fetch subcategories. Please try again.',
    //             ]);
    //         }
    //     }

    //     return $this->response->setStatusCode(400)->setJSON([
    //         'status' => 0,
    //         'msg' => 'Invalid request.',
    //     ]);
    // }

    public function getSubCategories()
{
    if ($this->request->isAJAX()) {
        try {
            $subcategoryModel = new SubCategory();

            // Fetch subcategories and join with parent category names
            $subcategories = $subcategoryModel
                ->select('sub_categories.id, sub_categories.name, sub_categories.ordering, sub_categories.description, categories.name as parent_category')
                ->join('categories', 'categories.id = sub_categories.parent_cat', 'left') // Left join for parent categories
                ->findAll();

            $data = [];
            foreach ($subcategories as $subcategory) {
                $postModel = new Post();
                $userPostModel = new \App\Models\UserPost();

                // Count admin posts
                $adminPostsCount = $postModel->where(['category_id' => $subcategory['id'], 'visibility' => 1])->countAllResults();

                // Count user-approved posts
                $userPostsCount = $userPostModel->where(['subcategory_id' => $subcategory['id'], 'status' => 1])->countAllResults();

                // Total count
                $totalPostsCount = $adminPostsCount + $userPostsCount;

                $data[] = [
                    'id' => $subcategory['id'],
                    'name' => $subcategory['name'],
                    'parent_category' => $subcategory['parent_category'] ?? 'Uncategorized',
                    'posts_count' => $totalPostsCount, // Updated to include both admin and user posts
                    'action' => "
                    <div class='btn-group'>
                        <button class='btn btn-sm btn-primary editSubCategoryBtn' data-id='{$subcategory['id']}'>Edit</button>
                        <button class='btn btn-sm btn-danger deleteSubCategoryBtn' data-id='{$subcategory['id']}'>Delete</button>
                    </div>",
                    'ordering' => $subcategory['ordering'],
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching subcategories: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to fetch subcategories. Please try again.',
            ]);
        }
    }

    return $this->response->setStatusCode(400)->setJSON([
        'status' => 0,
        'msg' => 'Invalid request.',
    ]);
}



    //     public function deleteSubCategory()
    // {
    //     if ($this->request->isAJAX()) {
    //         $id = $this->request->getVar('id'); // Get subcategory ID
    //         $subcategoryModel = new SubCategory();

    //         if ($subcategoryModel->delete($id)) {
    //             // Reorder the subcategories after deletion
    //             $this->reorderSubCategories();

    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'msg' => 'Subcategory deleted successfully and reordered.',
    //             ]);
    //         }

    //         return $this->response->setJSON([
    //             'status' => 0,
    //             'msg' => 'Failed to delete subcategory.',
    //         ]);
    //     }

    //     return $this->response->setStatusCode(400)->setJSON(['msg' => 'Invalid request.']);
    // }

    // private function reorderSubCategories()
    // {
    //     $subcategoryModel = new SubCategory();

    //     // Fetch all subcategories ordered by the current `ordering` column
    //     $subcategories = $subcategoryModel->orderBy('ordering', 'ASC')->findAll();

    //     // Reassign ordering starting from 1
    //     $ordering = 1;
    //     foreach ($subcategories as $subcategory) {
    //         $subcategoryModel->update($subcategory['id'], ['ordering' => $ordering]);
    //         $ordering++;
    //     }
    // }

    // public function deleteSubCategory()
    // {
    //     if ($this->request->isAJAX()) {
    //         $id = $this->request->getVar('id'); // Get the subcategory ID to delete
    //         $subcategoryModel = new SubCategory();

    //         // Delete the subcategory
    //         if ($subcategoryModel->delete($id)) {
    //             // Reorder the remaining subcategories
    //             $this->reorderSubCategories();

    //             return $this->response->setJSON([
    //                 'status' => 1,
    //                 'msg' => 'Subcategory deleted successfully and reordered.',
    //             ]);
    //         }

    //         return $this->response->setJSON([
    //             'status' => 0,
    //             'msg' => 'Failed to delete subcategory.',
    //         ]);
    //     }

    //     return $this->response->setStatusCode(400)->setJSON(['msg' => 'Invalid request.']);
    // }

    public function deleteSubCategory()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id'); // Get the subcategory ID to delete
            $subcategoryModel = new SubCategory();
            $postModel = new Post(); // Post model

            // Check for related posts
            $relatedPosts = $postModel->where('category_id', $id)->findAll();
            if ($relatedPosts) {
                $count = count($relatedPosts);
                $msg = $count === 1
                    ? "There is ($count) post related to this subcategory, so it cannot be deleted."
                    : "There are ($count) posts related to this subcategory, so it cannot be deleted.";

                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => $msg,
                ]);
            }

            // Delete the subcategory
            if ($subcategoryModel->delete($id)) {
                // Reorder the remaining subcategories
                $this->reorderSubCategories();

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Subcategory deleted successfully and reordered.',
                ]);
            }

            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to delete subcategory.',
            ]);
        }

        return $this->response->setStatusCode(400)->setJSON([
            'status' => 0,
            'msg' => 'Invalid request.',
        ]);
    }


    // private function reorderSubCategories()
    // {
    //     $subcategoryModel = new SubCategory();

    //     // Fetch all subcategories ordered by their current ordering (or id if ordering is inconsistent)
    //     $subcategories = $subcategoryModel->orderBy('ordering', 'ASC')->findAll();

    //     // Reassign ordering starting from 1
    //     $ordering = 1;
    //     foreach ($subcategories as $subcategory) {
    //         $subcategoryModel->update($subcategory['id'], ['ordering' => $ordering]);
    //         $ordering++;
    //     }
    // }

    private function reorderSubCategories()
    {
        $subcategoryModel = new \App\Models\SubCategory();

        // Fetch all subcategories ordered by their current ordering
        $subcategories = $subcategoryModel->orderBy('ordering', 'ASC')->findAll();

        if (!empty($subcategories)) {
            // Prepare a batch update for optimized reordering
            $updateData = [];
            $ordering = 1;

            foreach ($subcategories as $subcategory) {
                $updateData[] = [
                    'id' => $subcategory['id'],
                    'ordering' => $ordering,
                ];
                $ordering++;
            }

            // Perform a batch update to reorder
            $subcategoryModel->updateBatch($updateData, 'id');
        }
    }

    //update and edit subcategories

    public function getSubCategory()
    {
        if (!$this->request->isAJAX()) {
            log_message('error', 'Invalid request type');
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Invalid request type.',
            ]);
        }

        $id = $this->request->getVar('subcategory_id');

        if (!$id) {
            log_message('error', 'Subcategory ID not provided in request.');
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Subcategory ID is required.',
            ]);
        }

        $subcategoryModel = new SubCategory();
        $categoryModel = new Category();

        // Fetch the subcategory by ID
        $subcategory_data = $subcategoryModel->find($id);

        if (!$subcategory_data) {
            log_message('error', 'Subcategory not found for ID: ' . $id);
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Subcategory not found.',
            ]);
        }

        // Fetch all parent categories
        $parent_categories = $categoryModel->findAll();
        $options = $this->generateCategoryOptions($parent_categories, $subcategory_data['parent_cat']);

        // Return the response
        return $this->response->setJSON([
            'status' => 1,
            'data' => $subcategory_data,
            'parent_categories' => $options,
        ]);
    }

    /**
     * Generate HTML options for parent categories
     *
     * @param array $categories List of parent categories
     * @param int $selectedId Selected parent category ID
     * @return string HTML options for a select dropdown
     */
    private function generateCategoryOptions(array $categories, $selectedId): string
    {
        $options = '<option value="">Select Parent Category</option>';
        foreach ($categories as $category) {
            $selected = ($category['id'] == $selectedId) ? 'selected' : '';
            $options .= '<option value="' . $category['id'] . '" ' . $selected . '>' . esc($category['name']) . '</option>';
        }
        return $options;
    }



    public function updateSubCategory()
    {
        $request = \Config\Services::request(); // Get the request instance

        if ($request->isAJAX()) {
            $id = $request->getVar('subcategory_id'); // Get the subcategory ID from the request
            $subcategoryName = $request->getVar('subcategory_name');
            $parentCategoryId = $request->getVar('parent_cat');
            $description = $request->getVar('description');

            $validation = \Config\Services::validation(); // Get the validation service

            // Validation rules
            $validation->setRules([
                'subcategory_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Subcategory name is required.',
                    ],
                ],
                'parent_cat' => [
                    'rules' => 'required|integer',
                    'errors' => [
                        'required' => 'Parent category is required.',
                        'integer' => 'Invalid parent category.',
                    ],
                ],
            ]);

            // Check validation
            if (!$validation->run([
                'subcategory_name' => $subcategoryName,
                'parent_cat' => $parentCategoryId,
            ])) {
                // Validation failed
                $errors = $validation->getErrors();
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(), // Update CSRF token
                    'error' => $errors,
                ]);
            }

            // Check uniqueness manually
            $subcategoryModel = new \App\Models\SubCategory();
            $exists = $subcategoryModel
                ->where('name', $subcategoryName)
                ->where('id !=', $id)
                ->first();
            if ($exists) {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'error' => ['subcategory_name' => 'Subcategory name already exists.'],
                ]);
            }

            // Update the subcategory
            $update = $subcategoryModel->update($id, [
                'name' => $subcategoryName,
                'parent_cat' => $parentCategoryId,
                'description' => $description,
            ]);
            if ($update) {
                return $this->response->setJSON([
                    'status' => 1,
                    'token' => csrf_hash(),
                    'msg' => 'Subcategory updated successfully.',
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'token' => csrf_hash(),
                    'msg' => 'Subcategory update failed.',
                ]);
            }
        }

        return $this->response->setStatusCode(400)->setJSON(['msg' => 'Invalid request']);
    }



    public function addPost()
    {
        $subcategory = new SubCategory();
        $data = [
            'pageTitle' => 'Add New Post',
            'categories' => $subcategory->asObject()->findAll()
        ];

        return view('backend/pages/new-post', $data);
    }

    public function createPost()
    {
        $request = \Config\Services::request();

        if ($request->isAJAX()) {
            $validation = \Config\Services::validation();

            $this->validate([
                'title' => [
                    'rules' => 'required|is_unique[posts.title]',
                    'errors' => [
                        'required' => 'Post title is required',
                        'is_unique' => 'This post title already exists',
                    ],
                ],
                'content' => [
                    'rules' => 'required|min_length[20]',
                    'errors' => [
                        'required' => 'Post content is required',
                        'min_length' => 'Post content must have at least 20 characters',
                    ],
                ],
                'category' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Select a post category',
                    ],
                ],
                'featured_image' => [
                    'rules' => 'uploaded[featured_image]|is_image[featured_image]|max_size[featured_image,2048]',
                    'errors' => [
                        'uploaded' => 'Featured image is required',
                        'is_image' => 'Please select a valid image file type',
                        'max_size' => 'The image size must not exceed 2MB',
                    ],
                ],

            ]);
            if ($validation->run() === FALSE) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors,]);
            } else {
                // return $this->response->setJSON([ 'status' => 1, 'token' => csrf_hash(), 'msg' => 'Validated.', ]);

                $user_id = CiAuth::id();
                $path = 'images/posts/';
                $file = $this->request->getFile('featured_image');
                $filename = $file->getClientName();

                // Make post featured images folder if it does not exist
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                // Upload featured image
                if ($file->move($path, $filename)) {
                    // Create thumbnail image
                    \Config\Services::image()
                        ->withFile($path . $filename)
                        ->fit(150, 150, 'center')
                        ->save($path . 'thumb_' . $filename);

                    // Create resized image
                    \Config\Services::image()
                        ->withFile($path . $filename)
                        ->resize(450, 300, true, 'width')
                        ->save($path . 'resized_' . $filename);

                    // Save new post details
                    $post = new Post();
                    $data = array(
                        'author_id' => $user_id,
                        'category_id' => $request->getVar('category'),
                        'title' => $request->getVar('title'),
                        'slug' => Slugify::model(Post::class)->make($this->request->getVar('title')),
                        'content' => $this->request->getVar('content'),
                        'featured_image' => $filename,
                        'tags' => $this->request->getVar('tags'),
                        'meta_description' => $this->request->getVar('meta_description'),
                        'visibility' => $this->request->getVar('visibility'),
                    );
                    $save = $post->insert($data); // Insert the post data
                    $last_id = $post->getInsertID(); // Get the last inserted ID

                    if ($save) {
                        return $this->response->setJSON([
                            'status' => 1,
                            'token' => csrf_hash(),
                            'msg' => 'New blog post has been successfully created.',
                        ]);
                    } else {
                        return $this->response->setJSON([
                            'status' => 0,
                            'token' => csrf_hash(),
                            'msg' => 'Something went wrong.',
                        ]);
                    }
                } else {
                    // Return error response
                    return $this->response->setJSON([
                        'status' => 0,
                        'token' => csrf_hash(),
                        'msg' => 'Error uploading featured image.',
                    ]);
                }
            }
        }
    }

    public function allPosts()
    {
        $data = [
            'pageTitle' => 'All Posts'
        ];

        return view('backend/pages/all-posts.php', $data);
    }


    public function getPosts()
    {
        if ($this->request->isAJAX()) {
            $postModel = new Post();
            $subCategoryModel = new SubCategory();

            // Fetch all posts
            $posts = $postModel->findAll();

            // Prepare data for DataTable
            $data = [];
            foreach ($posts as $index => $post) {
                $categoryName = 'Unknown'; // Default if category is missing
                if ($post['category_id']) {
                    $category = $subCategoryModel->find($post['category_id']);
                    $categoryName = $category ? $category['name'] : $categoryName;
                }

                $visibility = $post['visibility'] == 1 ? 'Public' : 'Private';

                $featuredImage = $post['featured_image']
                    ? base_url('images/posts/thumb_' . $post['featured_image'])
                    : base_url('images/no-image.png');

                $actions = "
                     <div class='btn-group'>
                     <a href='#' class='btn btn-sm btn-link'>View</a>
                     
                     <button class='btn btn-sm btn-link editPostBtn' data-url='" . route_to('edit-post', $post['id']) . "'>Edit</button>
                     <button class='btn btn-sm btn-link deletePostBtn' data-id='{$post['id']}'>Delete</button>
                </div>
                ";


                $data[] = [
                    $index + 1,
                    "<img src='{$featuredImage}' alt='Featured Image' class='img-thumbnail' style='width:70px; height:auto;'>",
                    esc($post['title']),
                    esc($categoryName),
                    esc($visibility),
                    $actions,
                ];
            }

            return $this->response->setJSON([
                "draw" => intval($this->request->getVar('draw')),
                "recordsTotal" => count($posts),
                "recordsFiltered" => count($posts),
                "data" => $data
            ]);
        }

        return $this->response->setJSON(['error' => 'Invalid request'], 400);
    }


    public function editPost($id)
    {
        $postModel = new Post();
        $subCategoryModel = new SubCategory();

        // Fetch the post by ID
        $post = $postModel->find($id);

        if (!$post) {
            // If post is not found, redirect to all posts with an error message
            return redirect()->to(route_to('all-posts'))->with('error', 'Post not found.');
        }

        // Fetch all subcategories for the dropdown
        $categories = $subCategoryModel->findAll();

        $data = [
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'categories' => $categories
        ];

        return view('backend/pages/edit-post', $data);
    }


    public function updatePost($id)
    {
        $postModel = new Post();
        $validation = \Config\Services::validation();

        // Fetch the post to update
        $post = $postModel->find($id);
        if (!$post) {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Post not found.',
            ]);
        }

        // Validate the form input
        $validation->setRules([
            'title' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post title is required',
                ],
            ],
            'content' => [
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Post content is required',
                    'min_length' => 'Post content must have at least 20 characters',
                ],
            ],
            'category' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Post category is required',
                ],
            ],
            'meta_keywords' => ['rules' => 'permit_empty'],
            'meta_description' => ['rules' => 'permit_empty'],
            'tags' => ['rules' => 'permit_empty'],
            'featured_image' => [
                'rules' => 'is_image[featured_image]|max_size[featured_image,2048]',
                'errors' => [
                    'is_image' => 'Please upload a valid image file',
                    'max_size' => 'The image must not exceed 2MB',
                ],
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 0,
                'error' => $validation->getErrors(),
            ]);
        }

        $file = $this->request->getFile('featured_image');
        $path = 'images/posts/';
        $filename = $post['featured_image']; // Default to existing image

        if ($file && $file->isValid()) {
            // Delete the old image files
            if (file_exists($path . $filename)) {
                unlink($path . $filename);
            }
            if (file_exists($path . 'thumb_' . $filename)) {
                unlink($path . 'thumb_' . $filename);
            }
            if (file_exists($path . 'resized_' . $filename)) {
                unlink($path . 'resized_' . $filename);
            }

            // Upload the new file
            $filename = $file->getRandomName();
            $file->move($path, $filename);

            // Create a thumbnail
            \Config\Services::image()
                ->withFile($path . $filename)
                ->fit(150, 150, 'center')
                ->save($path . 'thumb_' . $filename);

            // Create a resized version
            \Config\Services::image()
                ->withFile($path . $filename)
                ->resize(450, 300, true, 'width')
                ->save($path . 'resized_' . $filename);
        }

        // Update post data
        $data = [
            'title' => $this->request->getVar('title'),
            'content' => $this->request->getVar('content'),
            'category_id' => $this->request->getVar('category'),
            'meta_keywords' => $this->request->getVar('meta_keywords'),
            'meta_description' => $this->request->getVar('meta_description'),
            'tags' => $this->request->getVar('tags'),
            'visibility' => $this->request->getVar('visibility'),
            'featured_image' => $filename,
        ];

        if ($postModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Post updated successfully.',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'Failed to update the post.',
            ]);
        }
    }


    // public function deletePost()
    // {
    //     if ($this->request->isAJAX()) {
    //         $postModel = new Post();

    //         // Get the post ID from the request
    //         $id = $this->request->getVar('id');

    //         // Fetch the post
    //         $post = $postModel->find($id);

    //         if (!$post) {
    //             return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found.']);
    //         }

    //         $path = 'images/posts/';

    //         // Delete associated images
    //         if (file_exists($path . $post['featured_image'])) {
    //             unlink($path . $post['featured_image']);
    //         }
    //         if (file_exists($path . 'thumb_' . $post['featured_image'])) {
    //             unlink($path . 'thumb_' . $post['featured_image']);
    //         }
    //         if (file_exists($path . 'resized_' . $post['featured_image'])) {
    //             unlink($path . 'resized_' . $post['featured_image']);
    //         }

    //         // Delete the post
    //         if ($postModel->delete($id)) {
    //             return $this->response->setJSON(['status' => 1, 'msg' => 'Post deleted successfully.']);
    //         } else {
    //             return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to delete post.']);
    //         }
    //     }

    //     return $this->response->setJSON(['status' => 0, 'msg' => 'Invalid request.'], 400);
    // }

    public function deletePost()
{
    if ($this->request->isAJAX()) {
        $postModel = new Post();

        // Get the post ID from the request
        $id = $this->request->getVar('id');

        // Fetch the post
        $post = $postModel->find($id);

        if (!$post) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found.']);
        }

        $path = FCPATH . 'images/posts/'; // Full path to the images directory

        // Delete associated images
        $imageVariants = [
            $post['featured_image'],
            'thumb_' . $post['featured_image'],
            'resized_' . $post['featured_image']
        ];

        foreach ($imageVariants as $image) {
            $filePath = $path . $image;
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }

        // Delete the post
        if ($postModel->delete($id)) {
            return $this->response->setJSON(['status' => 1, 'msg' => 'Post and associated images deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to delete post.']);
        }
    }

    return $this->response->setJSON(['status' => 0, 'msg' => 'Invalid request.'], 400);
}



    public function approveUserPost($postId)
    {
        $userPostModel = new \App\Models\UserPost();

        // Check if post exists
        $post = $userPostModel->find($postId);
        if (!$post) {
            return redirect()->back()->with('fail', 'Post not found.');
        }

        // Approve the post
        $userPostModel->update($postId, ['status' => 1]);

        return redirect()->back()->with('success', 'Post approved successfully.');
    }

    // public function rejectPost($postId)
    // {
    //     $userPostModel = new \App\Models\UserPost();

    //     // Validate post existence
    //     $post = $userPostModel->find($postId);
    //     if (!$post) {
    //         return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found.']);
    //     }

    //     // Update the post status to rejected
    //     $userPostModel->update($postId, ['status' => 2]);

    //     return $this->response->setJSON(['status' => 1, 'msg' => 'Post rejected successfully.']);
    // }



    public function deleteUserPost()
    {
        $postId = $this->request->getPost('post_id');
        $userPostModel = new \App\Models\UserPost();

        $post = $userPostModel->find($postId);

        if (!$post) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found.']);
        }

        $userPostModel->delete($postId);
        $userPostModel->update($postId, ['status' => 2]);

        return $this->response->setJSON(['status' => 1, 'msg' => 'Post deleted successfully.']);
    }
    public function viewUserPost($postId)
    {
        $userPostModel = new \App\Models\UserPost();
        $subCategoryModel = new \App\Models\SubCategory();
        $siteUserModel = new \App\Models\SiteUser();

        // Fetch the post as an object
        $post = $userPostModel->asObject()->find($postId);
        if (!$post) {
            return redirect()->back()->with('fail', 'Post not found.');
        }

        // Fetch additional data
        $subcategory = $subCategoryModel->asObject()->find($post->subcategory_id);
        $author = $siteUserModel->asObject()->find($post->user_id);

        // Add additional fields to the post object
        $post->subcategory_name = $subcategory ? $subcategory->name : 'N/A';
        $post->author_name = $author ? $author->name : 'Unknown';

        return view('backend/pages/view-user-post', ['post' => $post]);
    }

    public function reviewUserPosts()
    {
        $userPostModel = new \App\Models\UserPost();
        $subCategoryModel = new \App\Models\SubCategory();
        $siteUserModel = new \App\Models\SiteUser();

        // Paginate results
        $perPage = 10; // Number of posts per page
        $userPosts = $userPostModel
            ->asObject()
            ->where('status', 0)
            ->paginate($perPage);

        foreach ($userPosts as $post) {
            $subcategory = $subCategoryModel->asObject()->find($post->subcategory_id);
            $author = $siteUserModel->asObject()->find($post->user_id);

            $post->subcategory_name = $subcategory ? $subcategory->name : 'N/A';
            $post->author_name = $author ? $author->name : 'Unknown';
        }

        $data = [
            'userPosts' => $userPosts,
            'pager' => $userPostModel->pager,
        ];

        return view('backend/pages/review-user-posts', $data);
    }

    
    

public function approvedUserPosts()
{
    $userPostModel = new \App\Models\UserPost();
    $subCategoryModel = new \App\Models\SubCategory();
    $siteUserModel = new \App\Models\SiteUser();

    // Retrieve all posts pending approval
    $posts = $userPostModel->asObject()->where('status', 1)->findAll();

    foreach ($posts as $post) {
        $subcategory = $subCategoryModel->asObject()->find($post->subcategory_id);
        $author = $siteUserModel->asObject()->find($post->user_id);

        $post->subcategory_name = $subcategory ? $subcategory->name : 'N/A';
        $post->author_name = $author ? $author->name : 'Unknown';

        // Generate slug if it doesn't exist
        if (empty($post->slug)) {
            $post->slug = Slugify::model(UserPost::class)->make($post->title);

            // Update the post with the new slug
            $userPostModel->update($post->id, ['slug' => $post->slug]);
        }
    }

    return view('backend/pages/approved-user-posts', ['posts' => $posts]);
}


public function deleteApprovedUserPost()
{
    $postId = $this->request->getPost('post_id');
    $userPostModel = new \App\Models\UserPost();

    // Check if the post exists and is approved
    $post = $userPostModel->asObject()->find($postId);
    if (!$post) {
        return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found.']);
    }

    if ($post->status != 1) {
        return $this->response->setJSON(['status' => 0, 'msg' => 'Only approved posts can be deleted.']);
    }

    // Attempt to delete the post
    try {
        $userPostModel->delete($postId);

        // If the post has an associated featured image, delete it
        if (!empty($post->featured_image)) {
            $imagePath = WRITEPATH . 'images/posts/' . $post->featured_image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        return $this->response->setJSON(['status' => 1, 'msg' => 'Approved post deleted successfully.']);
    } catch (\Exception $e) {
        return $this->response->setJSON(['status' => 0, 'msg' => 'An error occurred while deleting the post.']);
    }
}
// public function manageUsers()
// {
//     $userModel = new \App\Models\SiteUser();

//     // Fetch all users from the `site_users` table
//     $users = $userModel->findAll();

//     // Pass users data to the view
//     return view('backend/pages/manage_users.php', [
//         'pageTitle' => 'Manage Site Users',
//         'users' => $users,
//     ]);
// }


// public function manageUsers()
// {
//     $userModel = new \App\Models\SiteUser();

//     // Check if there's a search query
//     $search = $this->request->getGet('search');
//     if ($search) {
//         // Search by name or email
//         $users = $userModel->like('name', $search)
//                            ->orLike('email', $search)
//                            ->findAll();
//     } else {
//         // Fetch all users
//         $users = $userModel->findAll();
//     }

//     return view('backend/pages/manage_users.php', [
//         'pageTitle' => 'Manage Site Users',
//         'users' => $users,
//     ]);
// }

public function manageUsers()
{
    $db = \Config\Database::connect();
    $builder = $db->table('site_users');

    // Select users with the count of their posts
    $builder->select('site_users.*, COUNT(user_posts.id) as post_count');
    $builder->join('user_posts', 'user_posts.user_id = site_users.id', 'left');
    $builder->groupBy('site_users.id');

    // Check if there's a search query
    $search = $this->request->getGet('search');
    if ($search) {
        $builder->like('site_users.name', $search);
        $builder->orLike('site_users.email', $search);
    }

    $users = $builder->get()->getResultArray();

    return view('backend/pages/manage_users.php', [
        'pageTitle' => 'Manage Site Users',
        'users' => $users,
    ]);
}


public function deleteUser($id)
{
    $userModel = new \App\Models\SiteUser();

    // Check if the user exists

    $user = $userModel->find($id);
    if (!$user) {
        return redirect()->to(route_to('admin.manage.users'))->with('fail', 'User not found.');
    }

    // Delete the user
    if ($userModel->delete($id)) {
        return redirect()->to(route_to('admin.manage.users'))->with('success', 'User deleted successfully.');
    }

    return redirect()->to(route_to('admin.manage.users'))->with('fail', 'Failed to delete user.');
}



public function editUser($id)
{
    $userModel = new \App\Models\SiteUser();
    $user = $userModel->find($id);

    if (!$user) {
        return redirect()->to(route_to('admin.manage.users'))->with('fail', 'User not found.');
    }

    return view('backend/pages/edit_user.php', ['user' => $user, 'pageTitle' => 'Edit User']);
}
public function updateUser($id)
{
    $userModel = new \App\Models\SiteUser();

    $data = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'is_verified' => $this->request->getPost('is_verified'),
    ];

    if ($userModel->update($id, $data)) {
        return redirect()->to(route_to('admin.manage.users'))->with('success', 'User updated successfully.');
    }

    return redirect()->to(route_to('admin.edit.user', $id))->with('fail', 'Failed to update user.');
}


}
