@if (is_front_page())
  <div class="entry-content w-full">
    @php(the_content())
  </div>
@else
  <article @php(post_class('prose prose-lg max-w-3xl mx-auto py-8'))>
    <header class="mb-8">
      <h1 class="entry-title font-display text-4xl md:text-5xl font-extrabold text-base-content leading-tight mb-4">
        {!! get_the_title() !!}
      </h1>
    </header>

    <div class="entry-content">
      @php(the_content())
    </div>

    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav my-6"><p>' . __('Páginas:', 'luciotorres') . '</p>', 'after' => '</nav>']) !!}
  </article>
@endif
