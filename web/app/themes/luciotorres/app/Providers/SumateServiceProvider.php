<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Súmate — handles the civic-commitment form.
 *
 * Registers:
 *   - "firma" custom post type (internal storage of submissions)
 *   - admin_post_lucio_firma handler (validates, sanitizes, persists)
 *   - admin_post_nopriv_lucio_firma (same, for logged-out users)
 */
class SumateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        add_action('init', [$this, 'registerPostType']);
        add_action('admin_post_lucio_firma', [$this, 'handle']);
        add_action('admin_post_nopriv_lucio_firma', [$this, 'handle']);
    }

    public function registerPostType(): void
    {
        register_post_type('firma', [
            'labels' => [
                'name' => __('Firmas', 'luciotorres'),
                'singular_name' => __('Firma', 'luciotorres'),
                'add_new_item' => __('Añadir firma', 'luciotorres'),
                'edit_item' => __('Ver firma', 'luciotorres'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-edit',
            'menu_position' => 25,
            'supports' => ['title', 'editor', 'custom-fields'],
            'capability_type' => 'post',
            'has_archive' => false,
            'rewrite' => false,
        ]);
    }

    public function handle(): void
    {
        if (! isset($_POST['lucio_firma_nonce'])
            || ! wp_verify_nonce($_POST['lucio_firma_nonce'], 'lucio_firma')) {
            $this->fail(__('Verificación de seguridad fallida. Recargá la página.', 'luciotorres'));
        }

        $name  = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $city  = isset($_POST['city']) ? sanitize_text_field(wp_unslash($_POST['city'])) : '';
        $role  = isset($_POST['role']) ? sanitize_key(wp_unslash($_POST['role'])) : 'ciudadano';
        $consent = ! empty($_POST['consent']);

        if ($name === '' || $email === '' || ! is_email($email) || ! $consent) {
            $this->fail(__('Por favor completá nombre, correo válido y aceptá los términos.', 'luciotorres'));
        }

        $allowed_roles = ['ciudadano', 'voluntario', 'prensa', 'donante'];
        if (! in_array($role, $allowed_roles, true)) {
            $role = 'ciudadano';
        }

        $postId = wp_insert_post([
            'post_type'    => 'firma',
            'post_status'  => 'publish',
            'post_title'   => sprintf('%s <%s>', $name, $email),
            'post_content' => sprintf(
                "Nombre: %s\nCorreo: %s\nCiudad: %s\nRol: %s\nFecha: %s",
                $name,
                $email,
                $city ?: '—',
                $role,
                current_time('mysql'),
            ),
        ], true);

        if (is_wp_error($postId)) {
            $this->fail(__('No pudimos registrar tu firma. Intentá de nuevo.', 'luciotorres'));
        }

        update_post_meta($postId, 'firma_email', $email);
        update_post_meta($postId, 'firma_city', $city);
        update_post_meta($postId, 'firma_role', $role);

        $referer = wp_get_referer();
        $redirect = $referer ? remove_query_arg(['firma', 'firma_msg'], $referer) : home_url('/');
        $redirect = add_query_arg('firma', 'ok', $redirect);

        wp_safe_redirect($redirect);
        exit;
    }

    private function fail(string $message): void
    {
        $referer = wp_get_referer();
        $redirect = $referer ? remove_query_arg(['firma', 'firma_msg'], $referer) : home_url('/');
        $redirect = add_query_arg(['firma' => 'error', 'firma_msg' => rawurlencode($message)], $redirect);
        wp_safe_redirect($redirect);
        exit;
    }
}
