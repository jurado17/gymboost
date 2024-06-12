@component('mail::message')
# Order Invoice

Thank you for your purchase!

**Order ID:** {{ $order->id }}

**Order Total:** ${{ number_format($order->total_price, 2) }}

@if($order->userAddress)
**Billing Address:**
{{ $order->userAddress->address1 }}<br>
{{ $order->userAddress->city }}, {{ $order->userAddress->country_code }}<br>
{{ $order->userAddress->zip_code }}
@else
**Billing Address:**
Not available.
@endif

**Order Items:**
@if($order->order_items && $order->order_items->isNotEmpty())
    @foreach($order->order_items as $item)
        - **{{ $item->product->name }}**: {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}
    @endforeach
@else
    No items available.
@endif



Thanks,<br>
{{ config('app.name') }}
@endcomponent
