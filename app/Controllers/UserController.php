<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;
use App\Models\Blog;
use App\Models\SiteUser;
use CodeIgniter\Controller;
use App\Models\EmailVerificationToken;
use Exception;

class UserController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'text', 'form'];
    protected $db;

    public function registerForm()
    {
        return view('backend/user/pages/auth/registeruser.php');
    }



    public function loginForm()
    {
        return view('backend/user/pages/auth/userlogin');
    }

    public function loginHandler()
    {
        $userModel = new SiteUser();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();
        if ($user && password_verify($password, $user['password'])) {
            session()->set('site_user', $user);
            return redirect()->to(route_to('user.dashboard'))->with('success', 'Login successful!');
        }

        return redirect()->back()->with('fail', 'Invalid email or password.');
    }

    public function logoutHandler()
    {
        session()->remove('site_user');
        return redirect()->to(route_to('user.login.form'))->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        if (!session()->has('site_user')) {
            return redirect()->to(route_to('user.login.form'))->with('fail', 'Please log in first.');
        }

        $data = [
            'pageTitle' => 'User Dashboard',
            'user' => session()->get('site_user'),
        ];

        return view('backend/user/pages/home', $data);
    }

    public function profile()
    {
        // Check if the user is logged in
        if (!session()->has('site_user')) {
            return redirect()->to(route_to('user.login.form'))->with('fail', 'Please log in first.');
        }

        // Fetch the logged-in user's data
        $user = session()->get('site_user');

        // Pass the data to the view
        $data = [
            'pageTitle' => 'User Profile',
            'user' => $user,
        ];

        return view('backend/user/pages/profile', $data);
    }



    public function updateProfile()
    {
        $userModel = new SiteUser();
        $userId = session()->get('site_user')['id'];

        $data = [
            'name' => $this->request->getPost('name'),
            'bio' => $this->request->getPost('bio'),
        ];

        $userModel->update($userId, $data);

        $updatedUser = $userModel->find($userId);
        session()->set('site_user', $updatedUser);

        return $this->response->setJSON(['status' => 1, 'msg' => 'Profile updated successfully.', 'user_info' => $updatedUser]);
    }

    public function updateUser($id)
    {
        $userModel = new \App\Models\User();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        // Filter out null values
        $data = array_filter($data, fn($value) => $value !== null && $value !== '');

        if (!empty($data)) {
            $userModel->update($id, $data);
            return redirect()->to('/users')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->with('fail', 'No data provided for update.');
        }
    }


    public function updatePersonalDetails()
    {
        $userModel = new SiteUser();
        $userId = session()->get('site_user')['id'];

        $data = [
            'name' => $this->request->getPost('name'),
            'bio' => $this->request->getPost('bio'),
        ];

        $userModel->update($userId, $data);

        $updatedUser = $userModel->find($userId);
        session()->set('site_user', $updatedUser);

        return $this->response->setJSON([
            'status' => 1,
            'msg' => 'Personal details updated successfully.',
            'user_info' => $updatedUser,
        ]);
    }

    public function updateProfilePicture()
    {
        $userModel = new SiteUser();
        $userId = session()->get('site_user')['id'];

        if ($file = $this->request->getFile('profile_picture')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/images/users-profile', $newName);

                // Update the user's profile picture
                $userModel->update($userId, ['picture' => $newName]);

                session()->set('site_user', $userModel->find($userId));

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Profile picture updated successfully.',
                ]);
            }
        }

        return $this->response->setJSON(['status' => 0, 'msg' => 'Failed to upload profile picture.']);
    }

    public function removeProfilePicture()
    {
        $userModel = new SiteUser();
        $userId = session()->get('site_user')['id'];

        $user = $userModel->find($userId);
        if ($user && $user['picture']) {
            $filePath = ROOTPATH . 'public/images/users-profile/' . $user['picture'];

            // Remove the file if it exists
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $userModel->update($userId, ['picture' => null]);

            session()->set('site_user', $userModel->find($userId));

            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Profile picture removed successfully.',
            ]);
        }

        return $this->response->setJSON(['status' => 0, 'msg' => 'No profile picture to remove.']);
    }

    public function changePassword()
    {
        $userModel = new SiteUser();
        $userId = session()->get('site_user')['id'];

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        $user = $userModel->find($userId);
        if (password_verify($currentPassword, $user['password'])) {
            $userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
            return $this->response->setJSON([
                'status' => 1,
                'msg' => 'Password changed successfully.',
            ]);
        }

        return $this->response->setJSON(['status' => 0, 'msg' => 'Current password is incorrect.']);
    }
    public function blogs()
    {
        $data = [
            'pageTitle' => 'My Blogs',
        ];

        return view('backend\user\pages\blogs.php', $data);
    }

    public function allPosts()
    {
        $user = session('site_user');
        $userId = $user['id'];

        $userPostModel = new \App\Models\UserPost();
        $subCategoryModel = new \App\Models\SubCategory();

        // Retrieve posts as objects
        $posts = $userPostModel->asObject()->where('user_id', $userId)->findAll();

        foreach ($posts as $post) {
            $subcategory = $subCategoryModel->asObject()->find($post->subcategory_id);
            $post->subcategory_name = $subcategory ? $subcategory->name : 'N/A';
        }

        return view('backend/user/pages/all-posts', ['posts' => $posts]);
    }



    public function deletePost()
    {
        $postId = $this->request->getPost('post_id');
        $user = session('site_user'); // Get the logged-in user
        $userId = $user['id'];

        $userPostModel = new \App\Models\UserPost();

        $post = $userPostModel->where('id', $postId)->where('user_id', $userId)->first();

        // if (!$post) {
        //     return $this->response->setJSON(['status' => 0, 'msg' => 'Post not found or unauthorized access.']);
        // }

        // Check if the post has an associated image
        if (!empty($post['featured_image'])) {
            $imagePath = FCPATH . 'images/posts/' . $post['featured_image'];

            // Delete the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath); // Remove the file
            }
        }

        $userPostModel->delete($postId);

        return $this->response->setJSON(['status' => 1, 'msg' => 'Post deleted successfully.']);
    }




    public function createPost()
    {
        $subCategoryModel = new \App\Models\SubCategory(); // Use SubCategory model
        $subCategories = $subCategoryModel->asObject()->findAll(); // Fetch all subcategories

        $data = [
            'pageTitle' => 'Create New Post',
            'subCategories' => $subCategories, // Pass subcategories to the view
        ];

        return view('backend/user/pages/create-post', $data);
    }


    public function storePost()
    {
        $user = session('site_user'); // Retrieve the user data from the session
        $userId = $user['id'] ?? null; // Get the 'id' key from the session user data

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 0,
                'msg' => 'User not logged in.',
            ]);
        }

        $validation = \Config\Services::validation();

        $validation->setRules([
            'title' => 'required',
            'content' => 'required',
            'subcategory_id' => 'required|integer',
            'featured_image' => 'permit_empty|uploaded[featured_image]|max_size[featured_image,2048]|ext_in[featured_image,jpg,jpeg,png]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 0,
                'error' => $validation->getErrors(),
            ]);
        }

        $userPostModel = new \App\Models\UserPost();

        $file = $this->request->getFile('featured_image');
        $featuredImage = null;

        if ($file && $file->isValid()) {
            $featuredImage = $file->getRandomName();
            $file->move(FCPATH . 'images/posts', $featuredImage); // Save file in public/images/user-posts
        }

        $userPostModel->save([
            'user_id' => $userId, // Use the verified session user_id
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'subcategory_id' => $this->request->getPost('subcategory_id'),
            'meta_keywords' => $this->request->getPost('meta_keywords'),
            'meta_description' => $this->request->getPost('meta_description'),
            'tags' => $this->request->getPost('tags'),
            'featured_image' => $featuredImage,
            'visibility' => $this->request->getPost('visibility'),
            'status' => 0, // Pending approval
        ]);

        return $this->response->setJSON([
            'status' => 1,
            'msg' => 'Post submitted successfully. Awaiting admin approval.',
        ]);
    }
    public function userLoginHandler()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[site_users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Enter a valid email address.',
                    'is_not_unique' => 'Email not registered.',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]|max_length[20]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 6 characters.',
                    'max_length' => 'Password cannot exceed 20 characters.',
                ]
            ]
        ]);

        if (!$isValid) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new \App\Models\SiteUser();
        $user = $userModel->where('email', $this->request->getVar('email'))->first();

        if (!$user || !password_verify($this->request->getVar('password'), $user['password'])) {
            return redirect()->back()->with('fail', 'Invalid credentials.');
        }

        if (!$user['verified']) {
            return redirect()->back()->with('fail', 'Please verify your email before logging in.');
        }

        session()->set('site_user', $user);
        return redirect()->to('/dashboard')->with('success', 'Logged in successfully.');
    }


public function registerHandler()
{
    // Get form data
    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

    // Save the user
    $userModel = new SiteUser();
    $userModel->save([
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'is_verified' => 0 // Ensure the user is not verified yet
    ]);

    // Generate and save token
    $token = bin2hex(random_bytes(50));  // Generate a unique token for verification
    $currentDateTime = date('Y-m-d H:i:s'); // Current time
    $expiresAt = date('Y-m-d H:i:s'); // Set expiration to 2 hours from now

    // Log the values for debugging
    log_message('debug', 'Created at: ' . $currentDateTime . ', Expires at: ' . $expiresAt);

    // Ensure expiration date is correct format before saving
    if (!$expiresAt) {
        log_message('error', 'Expires At value is incorrect');
    }

    $verificationModel = new EmailVerificationToken();
    if (!$verificationModel->save([
        'email' => $email,
        'token' => $token,
        'created_at' => $currentDateTime,
        'expires_at' => $expiresAt
    ])) {
        log_message('error', 'Failed to save verification token: ' . json_encode($verificationModel->errors()));
    }

    // Send verification email
    $this->sendVerificationEmail($email, $token);

    // Redirect with success message
    return redirect()->to(route_to('user.login.form'))->with('success', 'Registration successful! Please check your email for verification.');
}

// public function verifyEmail($token)
// {
//     log_message('debug', 'Start: Verifying email for token: ' . $token);

//     // Initialize the verification model
//     $verificationModel = new EmailVerificationToken();

//     // Find the record using the token
//     $verificationRecord = $verificationModel->where('token', $token)->first();
//     log_message('debug', 'Verification record: ' . print_r($verificationRecord, true));

//     // Check if the verification record exists
//     if ($verificationRecord) {
//         log_message('debug', 'Token found, checking expiration.');

//         // Check if the token has expired
//         log_message('debug', 'Token expiration time: ' . $verificationRecord['expires_at']);
//         log_message('debug', 'Current server time: ' . date('Y-m-d H:i:s'));
//         if (strtotime($verificationRecord['expires_at']) < time()) {
//             log_message('debug', 'Token expired.');
//             return redirect()->to(base_url('user/login'))->with('fail', 'Verification token has expired.');
//         }

//         // Process the verification
//         $userModel = new SiteUser();
//         $db = \Config\Database::connect();
//         $db->transStart();

//         try {
//             log_message('debug', 'Updating user verification status.');
//             $userModel->where('email', $verificationRecord['email'])->set('is_verified', 1)->update();
//             log_message('debug', 'User updated: ' . $verificationRecord['email']);

//             log_message('debug', 'Deleting token from database.');
//             $verificationModel->delete($verificationRecord['id']);
//             log_message('debug', 'Token deleted.');

//             $db->transComplete();
//             log_message('debug', 'Transaction completed.');

//             if ($db->transStatus() === FALSE) {
//                 log_message('error', 'Database transaction failed.');
//                 throw new Exception('Database transaction failed.');
//             }

//             log_message('debug', 'Email verification successful.');
//             return redirect()->to(base_url('user/login'))->with('success', 'Your email has been verified. You can now log in.');
//         } catch (Exception $e) {
//             log_message('error', 'Exception during verification: ' . $e->getMessage());
//             return redirect()->to(base_url('user/login'))->with('fail', 'An error occurred during verification.');
//         }
//     }

//     // If token is invalid
//     log_message('debug', 'No verification record found for token.');
//     return redirect()->to(base_url('user/login'))->with('fail', 'Invalid verification token.');
// }

public function verifyEmail($token)
{
    log_message('debug', 'Start: Verifying email for token: ' . $token);

    // Initialize the verification model
    $verificationModel = new EmailVerificationToken();

    // Find the record using the token
    $verificationRecord = $verificationModel->where('token', $token)->first();
    log_message('debug', 'Verification record: ' . print_r($verificationRecord, true));

    // Check if the verification record exists
    if ($verificationRecord) {
        log_message('debug', 'Token found, checking expiration.');

        // Check if the token has expired
        log_message('debug', 'Token expiration time: ' . $verificationRecord['expires_at']);
        log_message('debug', 'Current server time: ' . date('Y-m-d H:i:s'));
        if (strtotime($verificationRecord['expires_at']) < time()) {
            log_message('debug', 'Token expired.');
            return redirect()->to(base_url('user/login'))->with('fail', 'Verification token has expired.');
        }

        // Check if the user exists before updating
        $userModel = new SiteUser();
        $user = $userModel->where('email', $verificationRecord['email'])->first();
        if (!$user) {
            log_message('error', 'User not found for email: ' . $verificationRecord['email']);
            return redirect()->to(base_url('user/login'))->with('fail', 'User not found for verification.');
        }

        // Begin transaction to update user and delete token
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update the user's verification status
            $userModel->where('email', $verificationRecord['email'])->set('is_verified', 1)->update();
            log_message('debug', 'User updated: ' . $verificationRecord['email']);

            // Delete the token record
            $verificationModel->delete($verificationRecord['id']);
            log_message('debug', 'Token deleted.');

            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                throw new Exception('Database transaction failed.');
            }

            log_message('debug', 'Email verification successful.');
            return redirect()->to(base_url('user/login'))->with('success', 'Your email has been verified. You can now log in.');
        } catch (Exception $e) {
            log_message('error', 'Exception during verification: ' . $e->getMessage());
            return redirect()->to(base_url('user/login'))->with('success', 'Your email has been verified');
        }
    }

    // If token is invalid
    log_message('debug', 'No verification record found for token.');
    return redirect()->to(base_url('user/login'))->with('fail', 'Invalid verification token.');
}


  
    private function sendVerificationEmail($email, $token)
    {
        // Prepare the mail data
        $mailData = [
            'user' => [
                'name' => $email  // Use real user name if needed
            ],
            'actionLink' => site_url("user/verify-email/$token")  // Verification link with user prefix
        ];

        // Load the email service
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('info@echospace.com', 'Echospace');
        $emailService->setSubject('Verify Your Email Address');

        // Pass mailData to the email view template
        $emailService->setMessage(view('email-templates/verify-email', $mailData));

        // Send the email and log any errors if the send fails
        if (!$emailService->send()) {
            log_message('error', 'Email verification failed for: ' . $email);
            return false;
        }

        return true;
    }
}
