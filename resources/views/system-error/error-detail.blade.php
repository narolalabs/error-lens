<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ErrorLen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/themes/prism.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.css"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.js"></script>
</head>
<style>
    .error-page {
        max-height: 200px;
    }
</style>
<body>
    <h1>Error</h1>
    <div class="error-page">
        <pre class="line-numbers" data-line="{{ $line }}"><code class="language-php">{{ $fileContent }}</code></pre>
    </div>
</body>
</html>