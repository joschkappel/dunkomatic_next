<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-primary btn-md mr-2']) }} @if($disabled) disabled @endif>
    {{ $slot }}
</button>
