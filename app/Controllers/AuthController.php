<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CiAuth;
use App\Libraries\hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use PSpell\Config;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];

    public function loginForm()
    {
        $data = [
            'pagesTitle' => 'Login',
            'validations' => null
        ];
        return view('backend/pages/auth/login', $data);
    }

    public function loginHandler()
    {
        $fieldType = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Please, check the email field. It does not appear to be valid.',
                        'is_not_unique' => 'Email does not exist in our system.',
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[20]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 5 characters.',
                        'max_length' => 'Password must be at most 20 characters.'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_not_unique' => 'Username does not exist in our system.',
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[20]',
                    'errors' => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 5 characters.',
                        'max_length' => 'Password must be at most 20 characters.'
                    ]
                ]
            ]);
        }

        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pagesTitle' => 'Login',
                'validations' => $this->validator
            ]);
        } else {
            $user = new User();
            $userInfo = $user->where($fieldType, $this->request->getVar('login_id'))->first();
            $check_password = hash::check($this->request->getVar('password'), $userInfo['password']);

            if (!$check_password) {
                return redirect()->route('admin.login.form')->with('fail', 'Wrong password')->withInput();
            } else {
                CiAuth::setCiAuth($userInfo);
                return redirect()->route('admin.home');
            }
        }
    }
    public function forgotForm()
    {
        $data = array(
            'pageTitle' => 'Forgot Password',
            'validation' => null,
        );
        return view('backend\pages\auth\forgot.php', $data);
    }


    public function sendPasswordResetLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please, check the email field. It does not appear to be valid.',
                    'is_not_unique' => 'Email does not exist in our system.',
                ],
            ]
        ]);
        if (!$isValid) {
            return view('backend/pages/auth/forgot.php', [
                'pageTitle' => 'Forgot Password',
                'validation' => $this->validator,

            ]);
        } else {
            //get user admin details from
            $user = new User();
            $user_info = $user->asObject()->where('email', $this->request->getVar('email'))->first();

            //Generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            //Save token in password reset tokens table
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {
                // Update the existing token
                $password_reset_token->where('email', $user_info->email)
                    ->set([
                        'token' => $token,
                        'created_at' => Carbon::now(), // Ensure Carbon is imported or replace with Time::now()
                    ])
                    ->update();
            } else {
                // Insert a new token
                $password_reset_token->save([
                    'email' => $user_info->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
            }

            //create a action link
            //$actionLink = route_to('admin.reset-password',$token);
            $actionLink = base_url(route_to('admin.reset-password', $token));

            $mail_data = array(
                'actionLink' => $actionLink,
                'user' => $user_info,
            );

            $view = \Config\Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates\forget-email-template.php');

            $mailConfig = array(
                'mail_form_email' => env('MAIL_FORM_ADDRESS'),
                'mail_from_name' => env('MAIL_FORM_NAME'),
                'mail_recipient_email' => $user_info->email,
                'mail_recipient_name' => $user_info->name,
                'mail_subject' => 'Reset Password',
                'mail_body' => $mail_body,

            );

            //send mail notification
            if (sendEmail($mailConfig)) {
                return redirect()->route('admin.forgot.form')->with('success', 'Reset password link has been sent to your email address.');
            } else {
                return redirect()->route('admin.forgot.form')->with('fail', 'Something went wrong');
            }
        }
    }

    public function resetPassword($token)
    {
        // Use the correct model name
        $passwordResetToken = new PasswordResetToken();

        // Check if the token exists
        $check_token = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$check_token) { // If no token found
            return redirect()
                ->route('admin.forgot.form') // Ensure this matches your route alias in Routes.php
                ->with('fail', 'Invalid token. Request a new password reset link.');
        }

        // Check token expiration
        $diffMins = Carbon::parse($check_token->created_at)->diffInMinutes(Carbon::now());

        if ($diffMins > 1440) {
            return redirect()
                ->route('admin.forgot.form') // Ensure this matches your route alias in Routes.php
                ->with('fail', 'Token has expired. Request a new password reset link.');
        }

        // Show reset password form
        return view('backend\pages\auth\reset.php', [
            'pageTitle' => 'Reset Password',
            'validation' => null,
            'token' => $token,
        ]);
    }
    // public function resetPasswordHandler($token)
    // {
    //     $isValid = $this->validate([
    //         'new_password' => [
    //             'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
    //             'errors' => [
    //                 'required' => 'Enter new password',
    //                 'min_length' => 'New password must have at least 5 characters.',
    //                 'max_length' => 'New password must have a maximum of 20 characters.',
    //                 'is_password_strong' => 'New password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.',
    //             ],
    //         ],
    //         'confirm_new_password' => [
    //             'rules' => 'required|matches[new_password]',
    //             'errors' => [
    //                 'required' => 'Enter confirm new password',
    //                 'matches' => 'Passwords do not match.',
    //             ],
    //         ],
    //     ]);
    //     if (!$isValid) {
    //         return view('backend/pages/auth/reset.php', [
    //             'pageTitle' => 'Reset password',
    //             'validation' => null,
    //             'token' => $token,
    //         ]);
    //     } else {
    //         // Get token details
    //         $passwordResetToken = new PasswordResetToken();
    //         $get_token = $passwordResetToken->asObject()->where('token', $token)->first();

    //         // Get user (admin) details
    //         $user = new User();
    //         $user_info = $user->asObject()->where('email', $get_token->email)->first();

    //         if ($get_token === null) {
    //             return redirect()->back()->with('fail', 'Invalid token!')->withInput();
    //         } else {
    //             // Update admin password in DB
    //             $user->where('email', $user_info->email)
    //                 ->set(['password' => password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT)])
    //                 ->update();

    //             // Send notification to user (admin) email address
    //             $mail_data = array(
    //                  'user' => $user_info,
    //                 'new_password' => $this->request->getVar('new_password')
    //             );

    //             $view = \Config\Services::renderer();
    //             $mail_body = $view->setVar('mail_data', $mail_data)
    //                 ->render('email-templates\password-changed-email-template.php');

    //                 $mailConfig = [
    //                     'mail_from_email' => env('EMAIL_DEFAULT_FROM_EMAIL'),
    //                     'mail_from_name' => env('EMAIL_DEFAULT_FROM_NAME'),
    //                     'mail_recipient_email' => $user_info->email,
    //                     'mail_recipient_name' => $user_info->name,
    //                     'mail_subject' => 'Password Changed',
    //                     'mail_body' => $mail_body
    //                 ];

    //                 if (sendEmail($mailConfig)) {
    //                     // Delete token
    //                     $passwordResetToken->where('email', $user_info->email)->delete();

    //                     // Redirect and display message on login page
    //                     return redirect()->route('admin.login.form')->with('success', 'Done! Your password has been changed. Use the new password to log into the system.');
    //                 } else {
    //                     return redirect()->back()->with('fail', 'Something went wrong')->withInput();
    //                 }


    //         }
    //     }
    // }


    public function resetPasswordHandler($token)
    {
        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'Enter new password',
                    'min_length' => 'New password must have at least 5 characters.',
                    'max_length' => 'New password must have a maximum of 20 characters.',
                    'is_password_strong' => 'New password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.',
                ],
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Enter confirm new password',
                    'matches' => 'Passwords do not match.',
                ],
            ],
        ]);

        if (!$isValid) {
            return view('backend/pages/auth/reset.php', [
                'pageTitle' => 'Reset Password',
                'validation' => $this->validator,
                'token' => $token,
            ]);
        }

        // Retrieve token details
        $passwordResetToken = new PasswordResetToken();
        $get_token = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$get_token) {
            return redirect()->back()->with('fail', 'Invalid token!')->withInput();
        }

        // Update user password
        $user = new User();
        $userInfo = $user->asObject()->where('email', $get_token->email)->first();

        $newPassword = $this->request->getVar('new_password');
        $user->where('email', $userInfo->email)
            ->set(['password' => password_hash($newPassword, PASSWORD_DEFAULT)])
            ->update();

        // Send email notification
        $mailData = [
            'user' => $userInfo,                // User details
            'new_password' => $this->request->getVar('new_password'), // New password
        ];
        log_message('info', 'Mail Data: ' . json_encode($mailData));

        $view = \Config\Services::renderer();
        // $mailBody = $view->setVar('mail_data', $mailData)
        //                  ->render('email-templates/password-changed-email-template.php');

        // $mailConfig = [
        //     'mail_from_email' => env('EMAIL_DEFAULT_FROM_EMAIL'),
        //     'mail_from_name' => env('EMAIL_DEFAULT_FROM_NAME'),
        //     'mail_recipient_email' => $userInfo->email,
        //     'mail_recipient_name' => $userInfo->name,
        //     'mail_subject' => 'Password Changed',
        //     'mail_body' => $mailBody,
        // ];

        $mailBody = view('email-templates/password-changed-email-template', ['mail_data' => $mailData]);

        $mailConfig = [
            'mail_from_email' => env('EMAIL_DEFAULT_FROM_EMAIL'),
            'mail_from_name' => env('EMAIL_DEFAULT_FROM_NAME'),
            'mail_recipient_email' => $userInfo->email,
            'mail_recipient_name' => $userInfo->name,
            'mail_subject' => 'Password Changed',
            'mail_body' => $mailBody,
        ];

        if (sendEmail($mailConfig)) {
            // Delete token after successful password reset
            $passwordResetToken->where('email', $userInfo->email)->delete();

            // Redirect to login page with success message
            return redirect()->route('user.login.form')->with('success', 'Password changed successfully. Use the new password to log in.');
        } else {
            return redirect()->back()->with('fail', 'Failed to send email. Please try again.')->withInput();
        }
    }


    public function userForgotPasswordForm()
    {
        $data = [
            'pageTitle' => 'Forgot Password',
            'validation' => null,
        ];
        return view('backend/user/pages/auth/forget_password', $data);
    }

    // public function userSendPasswordResetLink()
    // {
    //     $isValid = $this->validate([
    //         'email' => [
    //             'rules' => 'required|valid_email|is_not_unique[site_users.email]',
    //             'errors' => [
    //                 'required' => 'Email is required',
    //                 'valid_email' => 'Please enter a valid email address.',
    //                 'is_not_unique' => 'This email is not registered in our system.',
    //             ],
    //         ]
    //     ]);

    //     if (!$isValid) {
    //         return view('backend/user/pages/auth/forget_password', [
    //             'pageTitle' => 'Forgot Password',
    //             'validation' => $this->validator,
    //         ]);
    //     }

    //     // Fetch user
    //     $userModel = new \App\Models\SiteUser();
    //     $user = $userModel->asObject()->where('email', $this->request->getVar('email'))->first();

    //     // Generate reset token
    //     $token = bin2hex(openssl_random_pseudo_bytes(65));

    //     // Save token in the database
    //     $passwordResetToken = new \App\Models\PasswordResetToken();
    //     $isOldTokenExists = $passwordResetToken->asObject()->where('email', $user->email)->first();

    //     if ($isOldTokenExists) {
    //         $passwordResetToken->where('email', $user->email)->set([
    //             'token' => $token,
    //             'created_at' => Carbon::now(),
    //         ])->update();
    //     } else {
    //         $passwordResetToken->save([
    //             'email' => $user->email,
    //             'token' => $token,
    //             'created_at' => Carbon::now(),
    //         ]);
    //     }

    //     // Send Reset Link via Email
    //     $resetLink = base_url(route_to('user.reset-password', $token));
    //     $mail_data = [
    //         'actionLink' => $resetLink,
    //         'user' => $user,
    //     ];
    //     $view = \Config\Services::renderer();
    //     $mail_body = $view->setVar('mail_data', $mail_data)
    //         ->render('email-templates/forget-email-template.php');

    //     $mailConfig = [
    //         'mail_from_email' => env('MAIL_FROM_ADDRESS'),
    //         'mail_from_name' => env('MAIL_FROM_NAME'),
    //         'mail_recipient_email' => $user->email,
    //         'mail_recipient_name' => $user->name,
    //         'mail_subject' => 'Reset Password',
    //         'mail_body' => $mail_body,
    //     ];

    //     if (sendEmail($mailConfig)) {
    //         return redirect()->route('user.forgot_password.form')
    //             ->with('success', 'A reset link has been sent to your email.');
    //     } else {
    //         return redirect()->route('user.forgot_password.form')
    //             ->with('fail', 'Failed to send the reset link. Please try again.');
    //     }
    // }

    public function userSendPasswordResetLink()
    {
        $isValid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[site_users.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please enter a valid email address.',
                    'is_not_unique' => 'This email is not registered in our system.',
                ],
            ]
        ]);

        if (!$isValid) {
            return view('backend/user/pages/auth/forget_password', [
                'pageTitle' => 'Forgot Password',
                'validation' => $this->validator,
            ]);
        }

        // Fetch user
        $userModel = new \App\Models\SiteUser();
        $user = $userModel->asObject()->where('email', $this->request->getVar('email'))->first();

        // Generate reset token
        $token = bin2hex(openssl_random_pseudo_bytes(65));

        // Save token in the database
        $passwordResetToken = new \App\Models\PasswordResetToken();
        $passwordResetToken->save([
            'email' => $user->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now(),
        ]);

        // Create reset link
        $resetLink = base_url(route_to('user.reset_password.form', $token));

        // Prepare email data
        $mailData = [
            'actionLink' => $resetLink,
            'user' => $user,
        ];

        $view = \Config\Services::renderer();
        $mailBody = $view->setVar('mail_data', $mailData)->render('email-templates/forget-email-template.php');

        $mailConfig = [
            'mail_from_email' => env('EMAIL_DEFAULT_FROM_EMAIL'),
            'mail_from_name' => env('EMAIL_DEFAULT_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Reset Your Password',
            'mail_body' => $mailBody,
        ];

        // Send email
        if (sendEmail($mailConfig)) {
            return redirect()->route('user.forgot_password.form')
                ->with('success', 'Password reset link has been sent to your email.');
        } else {
            return redirect()->route('user.forgot_password.form')
                ->with('fail', 'Failed to send the reset link. Please try again.');
        }
    }

    public function userResetPasswordForm($token)
    {
        $passwordResetToken = new \App\Models\PasswordResetToken();
        $resetData = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$resetData) {
            return redirect()->route('user.forgot_password.form')
                ->with('fail', 'Invalid or expired reset token.');
        }

        return view('backend/user/pages/auth/reset_password', [
            'pageTitle' => 'Reset Password',
            'validation' => null,
            'token' => $token,
        ]);
    }

    public function userResetPasswordHandler()
    {
        $token = $this->request->getPost('token');

        $isValid = $this->validate([
            'new_password' => [
                'rules' => 'required|min_length[5]|max_length[20]',
                'errors' => [
                    'required' => 'Enter your new password',
                    'min_length' => 'Password must have at least 5 characters.',
                    'max_length' => 'Password must have at most 20 characters.',
                ],
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Confirm your new password.',
                    'matches' => 'Passwords do not match.',
                ],
            ],
        ]);

        if (!$isValid) {
            return view('backend/user/pages/auth/reset_password', [
                'pageTitle' => 'Reset Password',
                'validation' => $this->validator,
                'token' => $token,
            ]);
        }

        $passwordResetToken = new \App\Models\PasswordResetToken();
        $resetData = $passwordResetToken->asObject()->where('token', $token)->first();

        if (!$resetData) {
            return redirect()->route('user.forgot_password.form')
                ->with('fail', 'Invalid or expired reset token.');
        }

        $userModel = new \App\Models\SiteUser();
        $user = $userModel->asObject()->where('email', $resetData->email)->first();

        // Update the password
        $hashedPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        $userModel->update($user->id, ['password' => $hashedPassword]);

        // Send notification email
        $mail_data = [
            'user' => $user,
        ];
        $view = \Config\Services::renderer();
        $mail_body = $view->setVar('mail_data', $mail_data)
            ->render('email-templates/password-changed-email-template.php');

        $mailConfig = [
            'mail_from_email' => env('MAIL_FROM_ADDRESS'),
            'mail_from_name' => env('MAIL_FROM_NAME'),
            'mail_recipient_email' => $user->email,
            'mail_recipient_name' => $user->name,
            'mail_subject' => 'Password Changed',
            'mail_body' => $mail_body,
        ];

        if (sendEmail($mailConfig)) {
            $passwordResetToken->where('email', $user->email)->delete();
            return redirect()->route('user.login.form')
                ->with('success', 'Password reset successfully.');
        } else {
            return redirect()->back()->with('fail', 'Failed to reset password. Please try again.');
        }
    }

    public function registerHandler()
{
    $validationRules = [
        'name' => [
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'Name is required',
                'min_length' => 'Name must have at least 3 characters',
                'max_length' => 'Name must have less than 50 characters',
            ]
        ],
        'email' => [
            'rules' => 'required|valid_email|is_unique[users.email]',
            'errors' => [
                'required' => 'Email is required',
                'valid_email' => 'Enter a valid email address',
                'is_unique' => 'Email already exists in the system',
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[5]|max_length[20]',
            'errors' => [
                'required' => 'Password is required',
                'min_length' => 'Password must be at least 5 characters',
                'max_length' => 'Password cannot exceed 20 characters',
            ]
        ],
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('fail', $this->validator->getErrors());
    }

    // If validation passes, proceed with user registration logic.
    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
    ];

    $userModel = new \App\Models\SiteUser();
    if ($userModel->insert($userData)) {
        return redirect()->to(route_to('user.login.form'))->with('success', 'Account created successfully. Please verify your email.');
    } else {
        return redirect()->back()->withInput()->with('fail', 'Failed to create account. Please try again.');
    }
}

public function userRegisterHandler()
{
    $siteUserModel = new \App\Models\SiteUser();
    $validation = $this->validate([
        'name' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[site_users.email]',
        'password' => 'required|min_length[5]',
    ]);

    if (!$validation) {
        return redirect()->back()->withInput()->with('fail', $this->validator->getErrors());
    }

    $data = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'email_verified' => 0, // Default: Not Verified
    ];

    $userId = $siteUserModel->insert($data);

    if ($userId) {
        // Generate verification token
        $verificationToken = bin2hex(random_bytes(32));
        $verificationModel = new \App\Models\EmailVerificationToken();
        $verificationModel->insert([
            'email' => $data['email'],
            'token' => $verificationToken,
        ]);

        // Send email with the verification link
        $verificationLink = base_url(route_to('user.verify.email', $verificationToken));
        $mailData = [
            'user' => $data,
            'actionLink' => $verificationLink,
        ];
        
        $emailBody = view('email-templates/verify-email.php', ['mailData' => $mailData]);
        
        sendEmail([
            'mail_recipient_email' => $data['email'],
            'mail_recipient_name' => $data['name'],
            'mail_subject' => 'Verify Your Account',
            'mail_body' => $emailBody,
        ]);

        return redirect()->back()->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    return redirect()->back()->with('fail', 'Registration failed! Please try again.');
}

    
}
