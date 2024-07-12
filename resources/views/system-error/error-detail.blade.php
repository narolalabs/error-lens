<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>ErrorLens - {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('vendor/error-lens/assets/img/favicon.png') }}" />

    <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/themes/prism.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/prism-themes/1.5.0/prism-material-light.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.css" />
    <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/style.css') }}" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark main_navbar">
        <div class="container">
            <a class="navbar-brand" href="">
                <img src="{{ asset('vendor/error-lens/assets/img/error-lens.png') }}" alt=""
                    style="width: 180px;" />
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarResponsive">

            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row" id="full-view">
            <div class="col-sm-12">
                <div id="sticky-wrap" class="my-4 d-flex flex-column full-log-view">
                    <h2>
                        @if ($errorCode)
                            <span class="badge rounded-pill bg-danger p-2">{{ $errorCode }}</span>
                        @endif {{ $errorLog->message }}
                    </h2>
                    <small>
                        <span class="fw-bold">Request URL:</span>
                        <span>({{ $errorLog->method }}) {{ $errorLog->url }}</span>
                    </small>
                </div>
            </div>
            <div class="col-md-12">
                <p class="fw-bolder">
                    {{ $errorFile }} :{{ $line }}
                </p>
                <div class="error-page mb-5">
                    <pre class="line-numbers" data-line="{{ $line }}"><code class="language-php">{{ $stack }}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.js">
    </script>
</body>

</html>
