@php
    $viteManifestPath = public_path('build/manifest.json');
    $viteManifest = file_exists($viteManifestPath)
        ? json_decode(file_get_contents($viteManifestPath), true)
        : [];
    $viteCss = $viteManifest['resources/css/app.css']['file'] ?? null;
    $viteJs = $viteManifest['resources/js/app.js']['file'] ?? null;
@endphp

@if ($viteCss)
    <link rel="stylesheet" href="{{ '/build/'.$viteCss }}">
@endif
@if ($viteJs)
    <script type="module" src="{{ '/build/'.$viteJs }}"></script>
@endif
