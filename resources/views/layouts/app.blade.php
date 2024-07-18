<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>ErrorLens - {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('vendor/error-lens/assets/img/favicon.png') }}" />

    <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/bootstrap.min.css') }}" />
    @yield('style')
    <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/style.css') }}" />
</head>

<body>
    @php
        $isUrlHttps = request()->secure();
        $isAssetUrlHttps = str_contains(asset(''), 'https://');
        $errorMessage = '';
        if ($isUrlHttps !== $isAssetUrlHttps) {
            if ($isUrlHttps === true && $isAssetUrlHttps === false) {
                $errorMessage = "Protocol mismatch issue: Your request URL is using the <span style='color: red'>HTTPS</span> protocol, while the asset URL is being requested via the <span style='color: red'>HTTP</span> protocol. This discrepancy prevents the assets from loading properly. Please resolve the protocol mismatch to ensure proper functionality.";
            } else {
                $errorMessage = "Protocol mismatch issue: Your request URL is using the <span style='color: red'>HTTP</span> protocol, while the asset URL is being requested via the <span style='color: red'>HTTPS</span> protocol. This discrepancy prevents the assets from loading properly. Please resolve the protocol mismatch to ensure proper functionality.";
            }
        }
    @endphp

    @if (!$errorMessage)
        <nav class="navbar navbar-expand-lg navbar-dark main_navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ route('error-lens.index') }}">
                    <img src="{{ asset('vendor/error-lens/assets/img/error-lens.png') }}" alt=""
                        style="width: 180px;" />
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarResponsive">
                    @php
                        function activeMenu($routeName)
                        {
                            return request()->route()->getName() === $routeName ? 'active' : '';
                        }
                    @endphp
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item {{ activeMenu('error-lens.index') }}">
                            <a class="nav-link text-white" href="{{ route('error-lens.index') }}">Dashboard</a>
                        </li>
                        <li class="nav-item {{ activeMenu('error-lens.archived.index') }}">
                            <a class="nav-link text-white" href="{{ route('error-lens.archived.index') }}">Archived</a>
                        </li>
                        <li class="nav-item {{ activeMenu('error-lens.config') }}">
                            <a class="nav-link text-white" href="{{ route('error-lens.config') }}">Config</a>
                        </li>
                        <li class="nav-item {{ activeMenu('error-lens.clear') }}">
                            <form action="{{ route('error-lens.clear') }}" method="post">
                                @csrf
                                <a class="nav-link text-white" id="clear-logs"
                                    href="{{ route('error-lens.clear') }}">Clear Logs</a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4">
            @yield('content')
        </div>

        <script src="{{ asset('vendor/error-lens/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/error-lens/assets/js/bootstrap.bundle.min.js') }}"></script>

        @yield('script')
        <script src="{{ asset('vendor/error-lens/assets/js/common.min.js') }}"></script>
        <script src="{{ asset('vendor/error-lens/assets/js/log.min.js') }}"></script>
    @else
        <h3>{!! $errorMessage !!}</h3>
    @endif
</body>

</html>
