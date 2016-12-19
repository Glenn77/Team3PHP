<!doctype html>
<html>
<head>
    <!-- my head section goes here -->
    <!-- my css and js goes here -->


    {{--<script src="http://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <script src="{{ elixir('js/app.js') }}"></script>
    <script
            src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
            integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">


    <title>@yield('title')</title>

</head>
<body>
    <header> @include('layouts.header') </header>
    <div class="contents"> @yield('content') </div>
    <footer> @include('layouts.footer') </footer>
</body>
<script type="text/javascript">
    var BASE_PATH = "{{ addslashes(base_path()) }}";
</script>
</html>
