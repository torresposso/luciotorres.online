<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action('wp_head', function () {
            $gaId = env('GOOGLE_ANALYTICS_ID');
            $metaId = env('META_PIXEL_ID');
            $fbDomainVerification = env('FACEBOOK_DOMAIN_VERIFICATION');

            if ($fbDomainVerification) {
                echo "<!-- Meta Domain Verification -->\n";
                echo "<meta name=\"facebook-domain-verification\" content=\"" . esc_attr($fbDomainVerification) . "\" />\n";
            }

            if (defined('WP_ENV') && WP_ENV === 'production' && ! is_user_logged_in()) {
                if ($gaId) {
                    $gaIdEscaped = esc_js($gaId);
                    echo "<!-- Google tag (gtag.js) -->\n";
                    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaIdEscaped}\"></script>\n";
                    echo "<script>\n";
                    echo "window.dataLayer = window.dataLayer || [];\n";
                    echo "function gtag(){dataLayer.push(arguments);}\n";
                    echo "gtag('js', new Date());\n";
                    echo "gtag('config', '{$gaIdEscaped}', { 'anonymize_ip': true });\n";
                    echo "</script>\n";
                }

                if ($metaId) {
                    $metaIdEscaped = esc_js($metaId);
                    echo "<!-- Meta Pixel Code -->\n";
                    echo "<script>\n";
                    echo "!function(f,b,e,v,n,t,s)\n";
                    echo "{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
                    echo "n.callMethod.apply(n,arguments):n.queue.push(arguments)};\n";
                    echo "if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\n";
                    echo "n.queue=[];t=b.createElement(e);t.async=!0;\n";
                    echo "t.src=v;s=b.getElementsByTagName(e)[0];\n";
                    echo "s.parentNode.insertBefore(t,s)}(window, document,'script',\n";
                    echo "'https://connect.facebook.net/en_US/fbevents.js');\n";
                    echo "fbq('init', '{$metaIdEscaped}');\n";
                    echo "fbq('track', 'PageView');\n";
                    echo "</script>\n";
                    echo "<noscript><img height=\"1\" width=\"1\" style=\"display:none\"\n";
                    echo "src=\"https://www.facebook.com/tr?id={$metaIdEscaped}&ev=PageView&noscript=1\"\n";
                    echo "/></noscript>\n";
                    echo "<!-- End Meta Pixel Code -->\n";
                }
            }
        }, 1);
    }
}
