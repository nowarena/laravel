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

    $(document).ready(function() {
        //
        // UPDATE NAV LINKS ACTIVE STATUS
        //
        function getPathFromUrl(url) {
            return url.split(/[?#]/)[0];
        }

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
            url = '/' + url_parts[3];
        }
        console.log(url);
        $('.nav-pills a[href="' + url + '"]').parents('li').addClass('active');
        //
        // END UPDATE NAV LINKS ACTIVE STATUS
        //

        //
        // UPDATE CATEGORY ON CHANGE
        //
        $('.catsDD').change(function () {

            var selectedCatId = $(this).val();
            var itemsCatsId = $(this).data("itemscatsid");
            var itemsId = $(this).data("itemsid");
            var data = {cats_id: selectedCatId, items_id: itemsId, items_cats_id:itemsCatsId};
            //("#form_" + itemId).('input:hidden[name="_token"]').val();
            var csrfToken = $('input:hidden[name="_token"]').val();
            console.log("csrfToken", csrfToken);
            console.log("data", data);
            updateItemCat(data, csrfToken);

        });

        function updateItemCat(data, csrfToken) {
            var request = $.ajax({
                method: "POST",
                url: '/items/updateitemcat',
                data: data,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
            });
            request.done(function (msg) {
                //alert("Data Saved: " + msg);
            });
            request.fail(function (jqXHR, textStatus) {
                //alert("Request failed: " + textStatus);
            });
        }



    });


</script>

</body>
</html>
