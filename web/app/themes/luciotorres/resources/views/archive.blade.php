@extends('layouts.app')

@section('content')
  <div class="border-b border-base-200 pb-8 mb-8">
    <span class="text-xs text-base-content/60 uppercase tracking-wider block mb-1">
      {{ __('Archivo', 'luciotorres') }}
    </span>
    <h1 class="entry-title text-4xl md:text-5xl font-extrabold text-base-content leading-tight">
      {!! $title !!}
    </h1>
    @if (!empty($description))
      <div class="text-base-content/70 mt-2 text-base font-serif italic">
        {!! $description !!}
      </div>
    @endif
  </div>

  @if (! have_posts())
    <div class="alert alert-warning my-4">
      {!! __('Lo siento, no se encontraron resultados.', 'luciotorres') !!}
    </div>
    {!! get_search_form(false) !!}
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-8">
    @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
    @endwhile
  </div>

  <div class="my-8">
    {!! get_the_posts_navigation() !!}
  </div>
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
