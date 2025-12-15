<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;

class CustomValidatePostSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Set PHP configuration at runtime
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');
        ini_set('memory_limit', '256M');
        
        // Get the maximum post size from PHP configuration
        $maxSize = $this->getPostMaxSize();
        
        // If max size is 0, no limit is set
        if ($maxSize > 0 && $request->server('CONTENT_LENGTH') > $maxSize) {
            throw new PostTooLargeException;
        }

        return $next($request);
    }
    
    /**
     * Get the maximum post size allowed by PHP configuration.
     *
     * @return int
     */
    protected function getPostMaxSize()
    {
        $postMaxSize = ini_get('post_max_size');
        
        if (is_numeric($postMaxSize)) {
            return (int) $postMaxSize;
        }
        
        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int) $postMaxSize;
        
        switch ($metric) {
            case 'K':
                return $postMaxSize * 1024;
            case 'M':
                return $postMaxSize * 1048576;
            case 'G':
                return $postMaxSize * 1073741824;
            default:
                return $postMaxSize;
        }
    }
}