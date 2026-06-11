<article @php(post_class('card bg-base-100 shadow-sm border border-base-200'))>
  @if (has_post_thumbnail())
    <figure class="aspect-video w-full overflow-hidden">
      <a href="{{ get_permalink() }}">
        {!! the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-300']) !!}
      </a>
    </figure>
  @endif

  <div class="card-body p-6">
    <header>
      <div class="text-xs text-base-content/60 mb-2">
        <time class="updated" datetime="{{ get_post_time('c', true) }}">
          {{ get_the_date() }}
        </time>
      </div>

      <h2 class="card-title text-xl font-bold">
        <a href="{{ get_permalink() }}" class="hover:text-primary transition-colors">
          {!! get_the_title() !!}
        </a>
      </h2>
    </header>

    <div class="entry-summary my-4 text-sm text-base-content/80">
      @php(the_excerpt())
    </div>
  </div>
</article>
