<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\Setting;
use CodeIgniter\Email\Email;
use App\Models\Blog;
use App\Models\SocialMedia;

class BlogController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions', 'text'];

    
    // public function index()
    // {
    //     $data = [
    //         'pageTitle' => get_settings()->blog_title, // Fetch blog title from settings
    //     ];

    //     return view('frontend/pages/home', $data); // Load the 'example' view from 'frontend' folder

    // }

    // public function categoryPosts($category_slug)
    // {
    //     $subcatModel = new \App\Models\SubCategory(); // Initialize the SubCategory model
    //     $postModel = new \App\Models\Post(); // Initialize the Post model

    //     // Fetch the category by slug as an object
    //     $subcategory = $subcatModel->asObject()->where('slug', $category_slug)->first();

    //     if (!$subcategory) {
    //         // If the category is not found, throw a 404 error
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Category not found");
    //     }

    //     $data = [];
    //     $data['pageTitle'] = 'Category: ' . $subcategory->name;
    //     $data['category'] = $subcategory;
    //     $data['page'] = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    //     $data['perPage'] = 6;

    //     // Calculate total posts in the category
    //     $data['total'] = count(
    //         $postModel->where('visibility', 1)
    //             ->where('category_id', $subcategory->id)
    //             ->findAll()
    //     );

    //     // Fetch paginated posts
    //     $data['posts'] = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->where('category_id', $subcategory->id)
    //         ->paginate($data['perPage']);

    //     // Attach pager object for pagination links
    //     $data['pager'] = $postModel->pager;

    //     // Return the view with data
    //     return view('frontend/pages/category_posts.php', $data);
    // }

    // public function tagPosts($tag)
    // {
    //     $postModel = new \App\Models\Post(); // Ensure the Post model is properly namespaced
    //     $data = [];

    //     // Set up data for the view
    //     $data['pageTitle'] = 'Tag: ' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8');
    //     $data['tag'] = htmlspecialchars($tag, ENT_QUOTES, 'UTF-8');
    //     $data['page'] = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    //     $data['perPage'] = 6;

    //     // Calculate total posts for the given tag
    //     $data['total'] = $postModel->where('visibility', 1)
    //         ->like('tags', $tag)
    //         ->countAllResults();

    //     // Fetch paginated posts for the given tag
    //     $data['posts'] = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->like('tags', $tag)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($data['perPage']);

    //     // Add the pager object for pagination links
    //     $data['pager'] = $postModel->pager;

    //     // Return the view with data
    //     return view('frontend/pages/tag_posts.php', $data);
    // }

    public function searchPosts()
    {
        $query = $this->request->getGet('query'); // Get the search query from the request

        // Check if the query is empty or null
        if (empty(trim($query))) {
            return view('frontend/pages/search_results', [
                'query' => null,
                'posts' => [],
                'error' => 'Please enter a valid search term.',
            ]);
        }

        $postModel = new \App\Models\Post();
        $perPage = 6; // Number of posts per page

        // Split query into individual words
        $keywords = explode(' ', trim($query));

        // Begin the query
        $postModel = $postModel->asObject()
            ->where('visibility', 1)
            ->groupStart();

        // Add conditions for each keyword
        foreach ($keywords as $word) {
            $postModel = $postModel->orLike('title', $word)
                ->orLike('content', $word);
        }

        $postModel = $postModel->groupEnd();

        // Fetch paginated results
        $posts = $postModel->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $pager = $postModel->pager; // Pager object for pagination links

        // Total results count
        $total = $postModel->where('visibility', 1)
            ->groupStart();

        foreach ($keywords as $word) {
            $total = $total->orLike('title', $word)
                ->orLike('content', $word);
        }

        $total = $total->groupEnd()
            ->countAllResults();

        // Pass results to the view
        return view('frontend/pages/search_results', [
            'query' => $query,
            'posts' => $posts,
            'pager' => $pager,
            'perPage' => $perPage,
            'total' => $total,
        ]);
    }


    public function __construct()
    {
        // Load the helper for all methods in this controller
        helper('post');
    }

    // public function readPost($slug)
    // {

    //     $postModel = new Post();
    //     $subCategoryModel = new SubCategory();

    //     // Fetch the post by slug
    //     $post = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->where('slug', $slug)
    //         ->first();

    //     if (!$post) {
    //         // If the post is not found, show a 404 error
    //         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Post not found");
    //     }

    //     // Fetch related posts by category (limit to 3)
    //     $relatedPosts = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->where('category_id', $post->category_id)
    //         ->where('id !=', $post->id) // Exclude the current post
    //         ->orderBy('created_at', 'desc')
    //         ->limit(3)
    //         ->findAll();

    //     // Fetch the category name
    //     $category = $subCategoryModel->asObject()
    //         ->find($post->category_id);

    //     // Fetch the previous post
    //     $previousPost = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->where('category_id', $post->category_id)
    //         ->where('id <', $post->id)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     // Fetch the next post
    //     $nextPost = $postModel->asObject()
    //         ->where('visibility', 1)
    //         ->where('category_id', $post->category_id)
    //         ->where('id >', $post->id)
    //         ->orderBy('id', 'asc')
    //         ->first();


    //     // Pass data to the view
    //     return view('frontend/pages/single_post', [
    //         'post' => $post,
    //         'category' => $category,
    //         'relatedPosts' => $relatedPosts,
    //         'previousPost' => $previousPost,
    //         'nextPost' => $nextPost,
    //     ]);
    // }

    public function contactUs()
    {
        $settingsModel = new Setting();
        $settings = $settingsModel->first();
        $socialMediaModel = new SocialMedia();
        $settings = $socialMediaModel->find(1);

        return view('frontend/pages/contact_us.php', ['settings' => $settings]);
    }

    public function contactUsSend()
    {
        // Validate form input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'message' => 'required|min_length[10]|max_length[1000]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare email data
        $mail_data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'message' => $this->request->getPost('message'),
        ];

        // Load the email library
        $email = \Config\Services::email();

        // Set email parameters
        $email->setFrom(getenv('EMAIL_DEFAULT_FROM_EMAIL'), getenv('EMAIL_DEFAULT_FROM_NAME'));
        $email->setTo('recipient@example.com'); // Replace with your recipient email
        $email->setSubject('New Contact Us Message');
        $email->setMessage(view('email-templates/contact-us-email-template', ['mail_data' => $mail_data]));

        // Send the email
        if ($email->send()) {
            return redirect()->back()->with('success', 'Your message has been sent successfully!');
        } else {
            $error = $email->printDebugger(['headers']);
            log_message('error', 'Email sending failed: ' . $error);
            return redirect()->back()->with('error', 'Failed to send your message. Debug Info: ' . $error);
        }
    }

    public function createForm()
    {
        return view('blog/create');
    }

    public function createHandler()
    {
        if (!session()->has('site_user')) {
            session()->setFlashdata('error', 'You need to log in first.');
            return redirect()->to('/user/login');
        }

        $blogModel = new \App\Models\Blog();
        $data = [
            'user_id' => session()->get('site_user')['id'],
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];
        $blogModel->insert($data);

        session()->setFlashdata('success', 'Your blog has been submitted for approval!');
        return redirect()->to('/blog/my-blogs');
    }

    public function myBlogs()
    {

        if (!session()->has('site_user')) {
            return redirect()->to('/user/login')->with('error', 'Please log in first.');
        }

        $blogModel = new \App\Models\Blog();
        $blogs = $blogModel->where('user_id', session()->get('site_user')['id'])->findAll();

        return view('blog/my_blogs', ['blogs' => $blogs]);
    }

    public function index()
    {
        $postModel = new \App\Models\Post(); // Admin posts
        $userPostModel = new \App\Models\UserPost(); // User posts

        $posts = array_merge(
            $postModel->asObject()
                ->where('visibility', 1)
                ->orderBy('created_at', 'desc')
                ->findAll(6),
            $userPostModel->asObject()
                ->where('status', 1) // Approved user posts
                ->orderBy('created_at', 'desc')
                ->findAll(6)
        );

        // Sort combined posts by created_at
        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $data = [
            'pageTitle' => get_settings()->blog_title,
            'posts' => $posts,
        ];

        return view('frontend/pages/home', $data);
    }

    public function categoryPosts($category_slug)
    {
        $subcatModel = new \App\Models\SubCategory();
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();

        $subcategory = $subcatModel->asObject()->where('slug', $category_slug)->first();
        if (!$subcategory) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Category not found");
        }

        $posts = array_merge(
            $postModel->asObject()
                ->where('visibility', 1)
                ->where('category_id', $subcategory->id)
                ->findAll(),
            $userPostModel->asObject()
                ->where('status', 1) // Approved user posts
                ->where('subcategory_id', $subcategory->id)
                ->findAll()
        );

        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $data = [
            'pageTitle' => 'Category: ' . $subcategory->name,
            'category' => $subcategory,
            'posts' => $posts,
        ];

        return view('frontend/pages/category_posts.php', $data);
    }
    public function tagPosts($tag)
    {
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();

        $posts = array_merge(
            $postModel->asObject()
                ->where('visibility', 1)
                ->like('tags', $tag)
                ->orderBy('created_at', 'desc')
                ->findAll(),
            $userPostModel->asObject()
                ->where('status', 1) // Approved user posts
                ->like('tags', $tag)
                ->orderBy('created_at', 'desc')
                ->findAll()
        );

        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $data = [
            'pageTitle' => 'Tag: ' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'),
            'tag' => htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'),
            'posts' => $posts,
        ];

        return view('frontend/pages/tag_posts.php', $data);
    }
    public function readPost($slug)
    {
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();
        $subCategoryModel = new \App\Models\SubCategory();

        // Fetch post from either table
        $post = $postModel->asObject()
            ->where('visibility', 1)
            ->where('slug', $slug)
            ->first() ??
            $userPostModel->asObject()
            ->where('status', 1) // Approved user posts
            ->where('slug', $slug)
            ->first();

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Post not found");
        }

        // Fetch related posts
        $relatedPosts = array_merge(
            $postModel->asObject()
                ->where('visibility', 1)
                ->where('category_id', $post->category_id ?? $post->subcategory_id)
                ->where('id !=', $post->id)
                ->orderBy('created_at', 'desc')
                ->findAll(3),
            $userPostModel->asObject()
                ->where('status', 1)
                ->where('subcategory_id', $post->category_id ?? $post->subcategory_id)
                ->where('id !=', $post->id)
                ->orderBy('created_at', 'desc')
                ->findAll(3)
        );

        $category = $subCategoryModel->asObject()
            ->find($post->category_id ?? $post->subcategory_id);

        return view('frontend/pages/single_post', [
            'post' => $post,
            'category' => $category,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function about()
    {
        $data = ['pageTitle' => 'About Us'];
        return view('frontend/pages/footer/about', $data);
    }

    public function privacyPolicy()
    {
        $data = ['pageTitle' => 'Privacy Policy'];
        return view('frontend/pages/footer/privacy_policy', $data);
    }

    public function termsConditions()
    {
        $data = ['pageTitle' => 'Terms & Conditions'];
        return view('frontend/pages/footer/terms_conditions', $data);
    }

    public function timePassGame()
    {
        $data = ['pageTitle' => 'TimePass Game'];
        return view('frontend/pages/footer/timepass_game', $data);
    }
}
