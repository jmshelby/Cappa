<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <title>Laravel Authentication Demo</title>
    <?php /* {{ HTML::style('/css/style.css') }} */ ?>
</head>
<body>
    <div id="container">
        <div id="nav">
            <ul>
                <li>{{ HTML::linkRoute('home', 'Home') }}</li>
                @if(Auth::check())
                    <li>{{ HTML::linkRoute('user.profile', 'Profile' ) }}</li>
                    <li>{{ HTML::linkRoute('cappa.dashboard', 'Cappa' ) }}</li>
                    <li>{{ HTML::linkRoute('user.logout', 'Logout ('.Auth::user()->username.')') }}</li>
                @else
                    <li>{{ HTML::linkRoute('user.login', 'Login') }}</li>
                @endif
            </ul>
        </div><!-- end nav -->

        <!-- check for flash notification message -->
        @if(Session::has('flash_notice'))
            <div id="flash_notice">{{ Session::get('flash_notice') }}</div>
        @endif

        @yield('content')
    </div><!-- end container -->
</body>
</html>
