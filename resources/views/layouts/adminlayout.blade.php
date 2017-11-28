<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
@include('layouts.partials.nav')
@include('layouts.partials.header')
@yield('content')
@include('layouts.partials.footer')
@include('layouts.partials.footer-scripts')

<script>
    function getPathFromUrl(url) {
        return url.split(/[?#]/)[0];
    }
    $(document).ready(function() {
        var url_full = location.href;
        // remove # and ? querystring stuff
        url_full = getPathFromUrl(url_full);
        console.log(url_full);
        var url_parts = url_full.split('/');
        console.log(url_parts);

        if (url_parts.length == 6) {
            // eg /tasks/6/edit
            url = '/' + url_parts[3] + '/' + url_parts[4] + '/' + url_parts[5];
        } else if (url_parts.length == 5) {
            // eg /tasks/create
            url = '/' + url_parts[3] + '/' + url_parts[4];
        } else if (url_parts.length == 4) {
            // eg. /tasks
            url ='/' + url_parts[3];
        }
        console.log(url);
        $('.nav-pills a[href="' + url + '"]').parents('li').addClass('active');
    });
</script>

</body>
</html>
