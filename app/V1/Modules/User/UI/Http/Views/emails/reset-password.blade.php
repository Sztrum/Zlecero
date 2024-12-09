@extends('core::layouts.emails.app')

@include('core::layouts.emails.header')

@include('core::layouts.emails.footer')

@section('main-content')
    <h1 style="margin-top: 0;margin-bottom: 24px; font-size: 20px; color: #121A26;">{{ __('user::emails.reset-password.html.title', ['userName' => $user->name]) }}</h1>
    <div style="font-weight: 400; font-size: 16px; line-height:24px; display: flex; flex-direction:column; row-gap: 20px">
        <p style="margin:0; padding: 0;">{{ __('user::emails.reset-password.html.paragraph-1') }}</p>
        <p style="margin:0; padding: 0;">{{ __('user::emails.reset-password.html.paragraph-2') }}</p>
        <p style="margin:0; padding: 0;">{{ __('user::emails.reset-password.html.paragraph-3') }}</p>
    </div>
    @include('core::layouts.emails.partials.button', [
       'link' => $frontendUrl,
       'text' => __('user::emails.reset-password.html.button-text')
    ])
@endsection
