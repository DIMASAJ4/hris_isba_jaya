@php
    // Read Vite manifest to find the correct hashed filenames
    $manifestPath = public_path('dist/manifest.json');
    $manifest = json_decode(file_get_contents($manifestPath), true);

    $cssFile  = $manifest['resources/css/app.css']['file'] ?? null;
    $jsFile   = $manifest['resources/js/app.js']['file']  ?? null;

    // Read the CSS content to inline it (bypass InfinityFree 403 on .css files)
    $cssContent = '';
    if ($cssFile && file_exists(public_path("dist/{$cssFile}"))) {
        $cssContent = file_get_contents(public_path("dist/{$cssFile}"));
    }
@endphp

{{-- Inline CSS to bypass InfinityFree 403 block on .css files --}}
@if($cssContent)
<style>{!! $cssContent !!}</style>
@endif

{{-- JS loads fine from external file --}}
@if($jsFile)
<script src="{{ asset("dist/{$jsFile}") }}" defer></script>
@endif
