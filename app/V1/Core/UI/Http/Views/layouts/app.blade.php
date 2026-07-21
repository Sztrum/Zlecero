<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @yield('head')

        @yield('standard_css')

        @yield('custom_css')

        @yield('standard_js_head')

        @yield('custom_js_head')
    </head>
    <body
        x-data="{}"

        x-init="
		    @if(session()->get('toast'))
				document.addEventListener('DOMContentLoaded', () => {
					@if(session()->get('toast')['type'] == "success")
						window.toastSuccess('{{ addslashes(session()->get('toast')['message']) }}');
					@else
						window.toastDanger('{{ addslashes(session()->get('toast')['message']) }}');
					@endif
				});
			@endif
        "
    >
        <main class="main-content">
            <section class="main-section">
                @yield('main-content')
            </section>
        </main>

        @yield('standard_js')

        @yield('custom_js')

        <x-core::spinner-fullscreen id="hidden-fullscreen-loader"/>
    </body>
</html>
