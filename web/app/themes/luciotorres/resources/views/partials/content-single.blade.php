<article @php(post_class('h-entry max-w-3xl mx-auto py-8'))>
  <header class="mb-8">
    <h1 class="entry-title font-display text-4xl md:text-5xl font-extrabold text-base-content leading-tight mb-4">
      {!! get_the_title() !!}
    </h1>

    <div class="entry-meta flex gap-4 text-sm text-base-content/60 my-2">
      <time class="dt-published" datetime="{{ get_post_time('c', true) }}">
        {{ get_the_date() }}
      </time>
      <span class="byline author vcard">
        {{ __('Por', 'luciotorres') }}
        <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" rel="author" class="fn font-bold hover:text-primary">
          {{ get_the_author() }}
        </a>
      </span>
    </div>
  </header>

  @if (has_post_thumbnail())
    <div class="featured-image mb-8 rounded-lg overflow-hidden shadow-md">
      {!! the_post_thumbnail('large', ['class' => 'w-full h-auto']) !!}
    </div>
  @endif

  <div class="entry-content prose prose-lg max-w-none font-serif text-lg leading-relaxed text-base-content/90 my-8">
    @php(the_content())
  </div>

  @if (has_tag())
    <footer class="mt-8 pt-4 border-t border-base-200">
      <p class="text-sm text-base-content/60">
        {{ __('Etiquetas:', 'luciotorres') }}
        {!! get_the_tag_list('', ', ', '') !!}
      </p>
    </footer>
  @endif

  @if (comments_open())
    <div class="comments-section mt-12">
      @php(comments_template())
    </div>
  @endif
</article>
