@extends('layouts.app')

@section('content')
  @include('partials.page-header')

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
