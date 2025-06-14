@php
    use Knuckles\Scribe\Tools\WritingUtils as u;
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{!! $metadata['title'] !!}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{!! $assetPathPrefix !!}css/theme-default.style.css" media="screen">
    <link rel="stylesheet" href="{!! $assetPathPrefix !!}css/theme-default.print.css" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    @if(isset($metadata['example_languages']))
        <style id="language-style">
            @foreach($metadata['example_languages'] as $lang)
                body .content .{{ $lang }}-example code { display: none; }
            @endforeach
        </style>
    @endif

    @if($tryItOut['enabled'] ?? true)
        <script>
            var tryItOutBaseUrl = "{!! $tryItOut['base_url'] ?? $baseUrl !!}";
            var useCsrf = Boolean({!! $tryItOut['use_csrf'] ?? null !!});
            var csrfUrl = "{!! $tryItOut['csrf_url'] ?? null !!}";
        </script>
        <script src="{{ u::getVersionedAsset($assetPathPrefix.'js/tryitout.js') }}"></script>
    @endif

    <script src="{{ u::getVersionedAsset($assetPathPrefix.'js/theme-default.js') }}"></script>
</head>

<body data-languages="{{ json_encode($metadata['example_languages'] ?? []) }}">

{{-- FORM INPUT TOKEN --}}
<div style="padding: 1rem; background-color: #f8f9fa; border-bottom: 1px solid #ddd; margin-bottom: 1rem;">
    <label for="jwt-token"><strong>Token :</strong></label>
    <input type="text" id="jwt-token" placeholder="Masukkan token JWT Anda"
           style="width: 60%; padding: 0.5rem; margin: 0 0.5rem;">
    <button onclick="authorizeWithToken()" style="padding: 0.5rem 1rem;">Send API Request</button>
</div>

@include("scribe::themes.default.sidebar")

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        {!! $intro !!}

        {!! $auth !!}

        @include("scribe::themes.default.groups")

        {!! $append !!}
    </div>
    <div class="dark-box">
        @if(isset($metadata['example_languages']))
            <div class="lang-selector">
                @foreach($metadata['example_languages'] as $name => $lang)
                    @php if (is_numeric($name)) $name = $lang; @endphp
                    <button type="button" class="lang-button" data-language-name="{{$lang}}">{{$name}}</button>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    // Fungsi untuk menyimpan token dan inject Authorization ke semua request
    function authorizeWithToken() {
        const token = document.getElementById('jwt-token').value;
        if (!token) return alert("Token tidak boleh kosong.");

        localStorage.setItem('scribe_bearer_token', token);

        // Override fetch agar semua request otomatis menyertakan header Authorization
        window.fetch = ((originalFetch) => {
            return (...args) => {
                args[1] = args[1] || {};
                args[1].headers = args[1].headers || {};
                args[1].headers['Authorization'] = 'Bearer ' + token;
                return originalFetch(...args);
            };
        })(window.fetch);

        alert("Token disimpan dan akan dikirim otomatis.");
    }

    // Load token jika sebelumnya pernah disimpan
    document.addEventListener('DOMContentLoaded', () => {
        const savedToken = localStorage.getItem('scribe_bearer_token');
        if (savedToken) {
            document.getElementById('jwt-token').value = savedToken;
            authorizeWithToken(); // otomatis inject
        }
    });
</script>

</body>
</html>
