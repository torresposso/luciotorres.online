<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'luciotorres'));
});

/**
 * Disable REST API user endpoints for anonymous users to prevent enumeration.
 */
add_filter('rest_authentication_errors', function ($result) {
    if (true === $result || is_wp_error($result)) {
        return $result;
    }

    $request_uri = sanitize_text_field($_SERVER['REQUEST_URI'] ?? '');
    $rest_route = sanitize_text_field($_GET['rest_route'] ?? '');

    if (! is_user_logged_in()) {
        if (str_contains($request_uri, '/wp/v2/users') || str_contains($rest_route, '/wp/v2/users')) {
            return new \WP_Error(
                'rest_cannot_access',
                __('Only authenticated users can access user endpoints.', 'luciotorres'),
                ['status' => rest_authorization_required_code()],
            );
        }
    }

    return $result;
});

/**
 * Block author enumeration scans via query parameter (?author=N).
 */
add_action('parse_request', function ($wp) {
    if (isset($_GET['author']) && ! is_user_logged_in()) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
});

/**
 * Validate that the given host is in the allowed list for dev URL rewriting.
 */
function is_allowed_dev_host(string $host): bool
{
    $allowed = [
        'localhost',
        '127.0.0.1',
    ];

    // Allow any 192.168.x.x or 10.x.x.x LAN IP
    if (preg_match('/^(192\.168\.\d{1,3}\.\d{1,3}|10\.\d{1,3}\.\d{1,3}\.\d{1,3})(:\d+)?$/', $host)) {
        return true;
    }

    // Strip port for comparison
    $hostWithoutPort = explode(':', $host)[0];
    return in_array($hostWithoutPort, $allowed, true);
}

/**
 * Dynamically adjust URLs in dev to match the request host if allowed.
 */
function rewrite_url_to_current_host(string $url): string
{
    $env = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';
    if (! in_array($env, ['local', 'development'], true)) {
        return $url;
    }

    if (! isset($_SERVER['HTTP_HOST']) || ! is_allowed_dev_host($_SERVER['HTTP_HOST'])) {
        return $url;
    }

    $parts = parse_url($url);
    if (! isset($parts['host']) || $parts['host'] === $_SERVER['HTTP_HOST']) {
        return $url;
    }

    $new_scheme = is_ssl() ? 'https' : 'http';
    $new_url = $new_scheme . '://' . $_SERVER['HTTP_HOST'];
    if (isset($parts['path'])) {
        $new_url .= $parts['path'];
    }
    if (isset($parts['query'])) {
        $new_url .= '?' . $parts['query'];
    }

    return $new_url;
}

/**
 * Dynamically adjust home_url in dev to match the request host.
 */
add_filter('home_url', fn($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Replace image host URLs in dev content to match the current request.
 */
function rewrite_content_image_hosts(string $content): string
{
    $currentHost = $_SERVER['HTTP_HOST'];
    $scheme = is_ssl() ? 'https' : 'http';

    $oldHosts = array_filter([
        'localhost:8080',
        'localhost:8000',
        function_exists('env') ? env('OLD_PRODUCTION_HOST') : ($_ENV['OLD_PRODUCTION_HOST'] ?? null),
    ]);

    $replaceHost = function ($url) use ($oldHosts, $currentHost, $scheme) {
        foreach ($oldHosts as $old) {
            if (str_contains($url, $old)) {
                return str_replace($old, $currentHost, str_replace('http://', $scheme . '://', $url));
            }
        }
        return $url;
    };

    return preg_replace_callback(
        '/https?:\/\/[^\s"\']+\.(jpg|jpeg|png|gif|webp|svg|avif)(\?[^\s"\']*)?["\'\s>]/i',
        fn($m) => $replaceHost($m[0]),
        $content,
    );
}

/**
 * Replace old /wp-content/uploads/ path with /app/uploads/ for Bedrock.
 */
function rewrite_content_upload_path(string $content): string
{
    return str_replace('/wp-content/uploads/', '/app/uploads/', $content);
}

/**
 * Fallback: rewrite resized image URLs to original when the resized file
 * does not exist on disk (e.g., after migration).
 */
function rewrite_content_thumbnail_fallback(string $content): string
{
    return preg_replace_callback(
        '/https?:\/\/[^\s"\']+\/app\/uploads\/([a-zA-Z0-9_\-\/]+\-\d+x\d+\.(?:jpg|jpeg|png|gif|webp|svg|avif))/i',
        function ($m) {
            static $fileExistsCache = [];

            $fullUrl = $m[0];
            $relativePathWithSuffix = $m[1];

            if (defined('WP_CONTENT_DIR')) {
                $uploadsDir = WP_CONTENT_DIR . '/uploads';
                $filePath = $uploadsDir . '/' . $relativePathWithSuffix;

                if (!isset($fileExistsCache[$filePath])) {
                    $fileExistsCache[$filePath] = file_exists($filePath);
                }

                if (! $fileExistsCache[$filePath]) {
                    $originalRelativePath = preg_replace('/-\d+x\d+(\.(?:jpg|jpeg|png|gif|webp|svg|avif))$/i', '$1', $relativePathWithSuffix);
                    $originalFilePath = $uploadsDir . '/' . $originalRelativePath;

                    if (!isset($fileExistsCache[$originalFilePath])) {
                        $fileExistsCache[$originalFilePath] = file_exists($originalFilePath);
                    }

                    if ($fileExistsCache[$originalFilePath]) {
                        return preg_replace('/-\d+x\d+(\.(?:jpg|jpeg|png|gif|webp|svg|avif))/i', '$1', $fullUrl);
                    }
                }
            }
            return $fullUrl;
        },
        $content,
    );
}

add_filter('the_content', function ($content) {
    $env = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';

    if (! in_array($env, ['local', 'development'], true)) {
        return $content;
    }

    if (! isset($_SERVER['HTTP_HOST']) || ! is_allowed_dev_host($_SERVER['HTTP_HOST'])) {
        return $content;
    }

    $content = rewrite_content_image_hosts($content);
    $content = rewrite_content_upload_path($content);
    $content = rewrite_content_thumbnail_fallback($content);

    return $content;
}, 999);

/**
 * Dynamically adjust site_url in dev to match the request host.
 */
add_filter('site_url', fn($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Dynamically adjust attachment URLs in dev to match the request host.
 */
add_filter('wp_get_attachment_url', fn($url) => rewrite_url_to_current_host($url), 10, 1);

/**
 * Dynamically adjust image srcset URLs in dev to match the request host.
 */
add_filter('wp_calculate_image_srcset', function ($sources) {
    foreach ($sources as &$source) {
        $source['url'] = rewrite_url_to_current_host($source['url']);
    }
    return $sources;
}, 10, 1);

/**
 * Dequeue Gutenberg block library styles on index, home, archive, and search views to optimize CSS delivery.
 */
add_action('wp_enqueue_scripts', function () {
    if (!is_single() && !is_page()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
    }
}, 100);
