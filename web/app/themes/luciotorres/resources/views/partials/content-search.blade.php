<article @php(post_class('border-b border-base-200 py-6'))>
  <header>
    <div class="text-xs text-base-content/60 mb-1">
      <time class="updated" datetime="{{ get_post_time('c', true) }}">
        {{ get_the_date() }}
      </time>
    </div>

    <h2 class="entry-title text-2xl font-bold">
      <a href="{{ get_permalink() }}" class="hover:text-primary transition-colors">
        {!! get_the_title() !!}
      </a>
    </h2>
  </header>

  <div class="entry-summary my-3 text-sm text-base-content/80">
    @php(the_excerpt())
  </div>
</article>
