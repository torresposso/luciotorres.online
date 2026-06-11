<footer class="content-info bg-neutral text-neutral-content py-8 px-6 mt-12 text-center">
  <p>&copy; {{ date('Y') }} {{ $siteName }}. {{ __('Todos los derechos reservados.', 'luciotorres') }}</p>
  @php(dynamic_sidebar('sidebar-footer'))
</footer>
