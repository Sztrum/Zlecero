<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    </head>
    <body  style="max-width: 544px; margin: auto; background-color: #EBEDEE; padding-top: 70px; padding-bottom: 70px; font-family: 'Poppins', serif;  font-weight: 400; font-style: normal;">
        @yield('header')

        <main class="main-content">
            <section class="main-section" style="padding: 40px; background-color: white; border-radius: 10px; ">
                @yield('main-content')
            </section>
        </main>

        @yield('footer')
    </body>
</html>
