@if (! post_password_required())
  <section id="comments" class="comments">
    @if ($responses())
      <h2>
        {!! $title !!}
      </h2>

      <ol class="comment-list">
        {!! $responses !!}
      </ol>

      @if ($paginated())
        <nav aria-label="Comment">
          <ul class="pager">
            @if ($previous())
              <li class="previous">
                {!! $previous !!}
              </li>
            @endif

            @if ($next())
              <li class="next">
                {!! $next !!}
              </li>
            @endif
          </ul>
        </nav>
      @endif
    @endif

    @if ($closed())
      <div class="alert alert-warning my-4">
        {!! __('Los comentarios están cerrados.', 'luciotorres') !!}
      </div>
    @endif

    @php(comment_form())
  </section>
@endif
