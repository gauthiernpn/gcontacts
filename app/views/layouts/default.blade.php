<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google Shared Contacts manager</title>
    <base href="{{asset('/')}}">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
<div class="container">
    @yield('content')
</div>

<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
@yield('scripts')


<!-- Piwik -->
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u=(("https:" == document.location.protocol) ? "https" : "http") + "://nder.be/piwik/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 6]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
        g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<noscript><p><img src="http://nder.be/piwik/piwik.php?idsite=6" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
</body>
</html>