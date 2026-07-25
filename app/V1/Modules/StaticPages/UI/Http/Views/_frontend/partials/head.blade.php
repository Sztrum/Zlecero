@section('head')
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="index,follow" />
    <meta name="description" content="{{ $meta['description'] }}" />
    <meta property="og:title" content="{{ $meta['title'] }}" />
    <meta property="og:description" content="{{ $meta['description'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="{{ $locale }}" />

    <title>{{ $meta['title'] }}</title>

    <link rel="canonical" href="{{ url()->current() }}" />
    @foreach($locales as $availableLocale)
        <link rel="alternate" hreflang="{{ $availableLocale }}" href="{{ url('/'.$availableLocale.($page === 'landing' ? '' : '/'.$page)) }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ url('/pl'.($page === 'landing' ? '' : '/'.$page)) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@include('core::layouts.styles')
