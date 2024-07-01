<x-mail::message>
{{ $formSubject }}

<x-mail::panel>
{{ $formMessage }}
</x-mail::panel>

Thanks, <br>
{{ config('app.name') }}
</x-mail::message>
