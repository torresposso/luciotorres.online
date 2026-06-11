@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div class="alert alert-warning my-4">
    {!! __('Lo siento, pero la página que intentas ver no existe.', 'luciotorres') !!}
  </div>

  {!! get_search_form(false) !!}
@endsection
