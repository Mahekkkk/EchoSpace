<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\CiAuth;

class Cifilter implements FilterInterface
{
    /**
     * This method runs before the request is processed.
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return \CodeIgniter\HTTP\RedirectResponse|void|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ensure arguments are provided and valid
        if (empty($arguments) || !isset($arguments[0])) {
            throw new \InvalidArgumentException('Filter argument is required (auth or guest).');
        }

        $type = $arguments[0];

        // Handle guest-only access
        if ($type === 'guest' && CiAuth::check()) {
            return redirect()->route('admin.home');
        }

        // Handle authenticated access
        if ($type === 'auth' && !CiAuth::check()) {
            return redirect()->route('admin.login.form')
                             ->with('fail', 'You must be logged in first.');
        }
    }

    /**
     * This method runs after the request is processed.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
