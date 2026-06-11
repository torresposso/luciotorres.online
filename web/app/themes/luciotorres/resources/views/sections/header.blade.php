<header class="banner bg-base-200 py-4 px-6 flex justify-between items-center">
  <a class="brand font-bold text-xl" href="{{ home_url('/') }}">
    {{ get_bloginfo('name') }}
  </a>

  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'flex gap-4', 'echo' => false]) !!}
    </nav>
  @endif
</header>
