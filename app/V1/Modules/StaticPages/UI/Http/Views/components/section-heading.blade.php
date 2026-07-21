@props([
    'title',
    'description' => null,
])

<header class="static-pages-section-heading">
    <h2>{{ $title }}</h2>

    @if($description)
        <p>{{ $description }}</p>
    @endif
</header>
