<!-- Sample Page for qr code -->
<form method="POST" action="/qr/generate">
    @csrf
    <input type="text" name="text" placeholder="Enter text">
    <button type="submit">Generate QR Code</button>
</form>

@if(isset($qrCode))
    <h3>QR Code for: {{ $text }}</h3>
    {!! $qrCode !!}
@endif
