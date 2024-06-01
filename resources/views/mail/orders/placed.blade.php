<x-mail::message>
    # OrderPlaced Succeffuly!

    Thank You for your order. your order number is: {{ $order->id }}

    <x-mail::button :url="'$url'">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
