<?php

use App\Libraries\CiAuth;
use App\Models\User;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\Post;
use App\Models\SiteUser;
use App\Models\UserPost;
use Carbon\Carbon;

if (!function_exists('get_user')) {
    function get_user()
    {
        if (CiAuth::check()) {
            $user = new User();
            return $user->asObject()->where('id', CiAuth::id())->first();
        } else {
            return null;
        }
    }
}
if (!function_exists('get_settings')) {
    function get_settings()
    {
        $settings = new Setting();
        $settings_data = $settings->asObject()->first();

        if (!$settings_data) {
            // Default settings in case no record exists
            $settings_data = (object) [
                'blog_title' => 'EchoSpace',
                'blog_email' => 'info@echospace.com',
                'blog_phone' => '',
                'blog_meta_keywords' => '',
                'blog_meta_description' => '',
                'blog_logo' => '',
                'blog_favicon' => '',
            ];
            $settings->save($settings_data);

            $new_settings_data = $settings->asObject()->first();

            return $new_settings_data;
        } else {
            return $settings_data;
        }
    }
}

if (!function_exists('get_social_media')) {
    function get_social_media()
    {
        $result = null;

        // Load the SocialMedia model
        $social_media = new SocialMedia();

        // Fetch the first record
        $social_media_data = $social_media->asObject()->first();

        // If no data exists, create default social media links
        if (!$social_media_data) {
            $defaultData = [
                'facebook_url'   => null,
                'twitter_url'    => null,
                'instagram_url'  => null,
                'linkedin_url'   => null,
                'github_url'     => null,
                'youtube_url'    => null,
            ];

            // Insert the default data into the database
            $social_media->save($defaultData);

            // Fetch the newly created data
            $new_social_media_data = $social_media->asObject()->first();
            $result = $new_social_media_data;
        } else {
            // If data exists, return it
            $result = $social_media_data;
        }

        return $result;
    }
}
if (!function_exists('current_route_name')) {
    /**
     * Get the current route name
     *
     * @return string|null Returns the route name if defined, null otherwise
     */
    function current_route_name()
    {
        // Get the current router instance
        $router = \CodeIgniter\Config\Services::router();

        // Check if the current route has a name
        $routeOptions = $router->getMatchedRouteOptions();

        if (isset($routeOptions['as'])) {
            return $routeOptions['as']; // Return the named route
        }

        return null; // No route name found
    }
}


if (!function_exists('get_parent_categories')) {
    /**
     * Get all parent categories ordered by 'ordering'
     *
     * @return array
     */
    function get_parent_categories()
    {
        $category = new \App\Models\Category();
        return $category->asObject()
            ->orderBy('ordering', 'asc')
            ->findAll();
    }
}

if (!function_exists('get_subcategories_by_parent_category_id')) {
    /**
     * Get all subcategories by parent category ID, ordered by 'ordering'
     *
     * @param int $id Parent category ID
     * @return array
     */
    function get_subcategories_by_parent_category_id($id)
    {
        $subcategory = new \App\Models\SubCategory();
        return $subcategory->asObject()
            ->orderBy('ordering', 'asc')
            ->where('parent_cat', $id)
            ->findAll();
    }
}

if (!function_exists('get_dependent_sub_categories')) {
    /**
     * Get all dependent subcategories (subcategories without a parent category)
     *
     * @return array
     */
    function get_dependent_sub_categories()
    {
        $subcategory = new \App\Models\SubCategory();
        return $subcategory->asObject()
            ->orderBy('ordering', 'asc')
            ->where('parent_cat', 0) // Subcategories with parent_cat = 0
            ->findAll();
    }
}


if (!function_exists('date_formatter')) {
    function date_formatter($date)
    {
        try {
            // Attempt to parse the date
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->isoFormat('MMM D, YYYY');
        } catch (\Exception $e) {
            // If the date is invalid, use the current date
            return Carbon::now()->isoFormat('MMM D, YYYY');
        }
    }
}


if (!function_exists('get_reading_time')) {
    function get_reading_time($content)
    {
        // Strip HTML tags and count words
        $word_count = str_word_count(strip_tags($content));

        // Average reading speed: 200 words per minute
        $words_per_minute = 200;

        // Calculate reading time in minutes
        // $minutes = ceil($word_count / $words_per_minute);
        $m = ceil($word_count / $words_per_minute);
        // Return formatted reading time
        // return $minutes <= 1 ? $minutes . ' Min read' : $minutes . ' Mins read';
        return $m <= 1 ? $m . ' Min read' : $m . ' Mins read';
    }
}



if (!function_exists('limit_words')) {
    function limit_words($content = null, $limit = 20)
    {
        $content = preg_replace("/<img[^>]+\>/i", "", $content);
        return word_limiter($content, $limit);
    }
}



// if (!function_exists('get_home_main_latest_post')) {
//     function get_home_main_latest_post()
//     {
//         $post = new Post();

//         $latestPost = $post->asObject()
//             ->where('visibility', 1) // Ensure the post is visible
//             ->orderBy('created_at', 'desc') // Order by latest creation time
//             ->first();

//         // Return null if no valid post found
//         return $latestPost ?: null;
//     }
// }



if (!function_exists('get_home_main_latest_post')) {
    function get_home_main_latest_post()
    {
        $postModel = new \App\Models\Post(); // Admin posts
        $userPostModel = new \App\Models\UserPost(); // User posts

        // Fetch the latest admin and user posts
        $latestAdminPost = $postModel->asObject()
            ->where('visibility', 1)
            ->orderBy('created_at', 'desc')
            ->first();

        $latestUserPost = $userPostModel->asObject()
            ->where('status', 1) // Approved user posts
            ->orderBy('created_at', 'desc')
            ->first();

        // Return the most recent post between admin and user posts
        if ($latestAdminPost && $latestUserPost) {
            return strtotime($latestAdminPost->created_at) > strtotime($latestUserPost->created_at)
                ? $latestAdminPost
                : $latestUserPost;
        }

        return $latestAdminPost ?? $latestUserPost;
    }
}









// if (!function_exists('get_6_home_latest_posts')) {
//     function get_6_home_latest_posts()
//     {
//         $post = new Post();
//         return $post->asObject()
//             ->where('visibility', 1)
//             ->limit(8, 0) // Adjusted limit: fetch 6 posts starting from the 0th index
//             ->orderBy('created_at', 'desc')
//             ->get()
//             ->getResult(); // Corrected method to get the results
//     }
// }

if (!function_exists('get_6_home_latest_posts')) {
    function get_6_home_latest_posts()
    {
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();

        // Fetch admin and user posts
        $adminPosts = $postModel->asObject()
            ->where('visibility', 1)
            ->orderBy('created_at', 'desc')
            ->findAll(6);

        $userPosts = $userPostModel->asObject()
            ->where('status', 1) // Approved user posts
            ->orderBy('created_at', 'desc')
            ->findAll(6);

        // Merge and sort posts
        $posts = array_merge($adminPosts, $userPosts);
        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        return array_slice($posts, 0, 6); // Return the top 6 posts
    }
}




/**
 * Sidebar random posts
 */
// if (!function_exists('get_sidebar_random_posts')) {

//     function get_sidebar_random_posts($max = 5)
//     {
//         $postModel = new Post(); // Assuming `Post` is the name of your model
//         return $postModel->asObject()
//             ->where('visibility', 1)
//             ->orderBy('RAND()')
//             ->limit($max)
//             ->get()
//             ->getResult();
//     }
// }

if (!function_exists('get_sidebar_random_posts')) {
    function get_sidebar_random_posts($max = 5)
    {
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();

        // Fetch random admin and user posts
        $adminPosts = $postModel->asObject()
            ->where('visibility', 1)
            ->orderBy('RAND()')
            ->limit($max)
            ->findAll();

        $userPosts = $userPostModel->asObject()
            ->where('status', 1) // Approved user posts
            ->orderBy('RAND()')
            ->limit($max)
            ->findAll();

        // Merge and shuffle posts
        $posts = array_merge($adminPosts, $userPosts);
        shuffle($posts);

        return array_slice($posts, 0, $max); // Return the top random posts
    }
}

if (!function_exists('get_combined_posts')) {
    function get_combined_posts($limit = 10, $orderBy = 'created_at', $orderDir = 'desc')
    {
        $postModel = new \App\Models\Post();
        $userPostModel = new \App\Models\UserPost();

        // Fetch admin and user posts
        $adminPosts = $postModel->asObject()
            ->where('visibility', 1)
            ->orderBy($orderBy, $orderDir)
            ->findAll($limit);

        $userPosts = $userPostModel->asObject()
            ->where('status', 1) // Approved user posts
            ->orderBy($orderBy, $orderDir)
            ->findAll($limit);

        // Merge and sort posts
        $posts = array_merge($adminPosts, $userPosts);
        usort($posts, function ($a, $b) use ($orderBy, $orderDir) {
            return $orderDir === 'desc'
                ? strtotime($b->$orderBy) - strtotime($a->$orderBy)
                : strtotime($a->$orderBy) - strtotime($b->$orderBy);
        });

        return array_slice($posts, 0, $limit); // Return the limited number of posts
    }
}



/** Sidebar categories */
if (!function_exists('get_sidebar_categories')) {
    function get_sidebar_categories()
    {
        $subcat = new \App\Models\SubCategory(); // Ensure the SubCategory model is properly namespaced
        return $subcat->asObject()
            ->orderBy('name', 'asc')
            ->findAll();
    }
}


/** Count posts by category ID */
if (!function_exists('posts_by_category_id')) {
    function posts_by_category_id($categoryId)
    {
        $postModel = new \App\Models\Post(); // Ensure the Post model is properly namespaced
        $posts = $postModel->where('visibility', 1)
            ->where('category_id', $categoryId)
            ->findAll();
        return count($posts);
    }
}

/** Sidebar Latest Posts */
if (!function_exists('sidebar_latest_posts')) {
    function sidebar_latest_posts($except = null)
    {
        $postModel = new Post(); // Ensure Post model is properly namespaced
        $query = $postModel->where('visibility', 1);

        // Exclude a specific post if $except is provided
        if ($except !== null) {
            $query->where('id !=', $except);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->getResult();
    }
}

/** ALL tags from posts table */
if (!function_exists('get_tags')) {
    function get_tags()
    {
        $postModel = new \App\Models\Post(); // Ensure the Post model is properly namespaced
        $tagsArray = [];

        // Fetch all posts with visibility = 1 and non-empty tags
        $posts = $postModel->asObject()
            ->where('visibility', 1)
            ->where('tags !=', "")
            ->orderBy('created_at', 'desc')
            ->findAll();

        // Extract tags from each post
        foreach ($posts as $post) {
            array_push($tagsArray, $post->tags);
        }

        // Combine all tags into a single string and split into individual tags
        $tagsList = implode(',', $tagsArray);

        // Return unique, trimmed tags as an array
        return array_unique(
            array_map('trim', array_filter(explode(',', $tagsList), 'trim'))
        );
    }


    if (!function_exists('generate_post_links')) {
        /**
         * Generates Previous and Next Post Links
         *
         * @param object|null $previousPost
         * @param object|null $nextPost
         * @return string HTML for Previous and Next Post Links
         */
        function generate_post_links($previousPost = null, $nextPost = null)
        {
            $html = '<div class="prev-next-posts mt-3 mb-3">';
            $html .= '<div class="row justify-content-between p-4">';

            // Previous Post
            $html .= '<div class="col-md-6 mb-2">';
            $html .= '<div>';
            $html .= '<h4>&laquo; Previous</h4>';
            if ($previousPost) {
                $html .= '<a href="' . route_to('read-post', $previousPost->slug) . '">' . esc($previousPost->title) . '</a>';
            } else {
                $html .= '<span>No Previous Post</span>';
            }
            $html .= '</div>';
            $html .= '</div>';

            // Next Post
            $html .= '<div class="col-md-6 mb-2 text-end">';
            $html .= '<div>';
            $html .= '<h4>Next &raquo;</h4>';
            if ($nextPost) {
                $html .= '<a href="' . route_to('read-post', $nextPost->slug) . '">' . esc($nextPost->title) . '</a>';
            } else {
                $html .= '<span>No Next Post</span>';
            }
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }
    }
}
