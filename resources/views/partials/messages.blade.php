<div id="messages">
    @if($msg = session("message:success"))
        <div class="success">
            {{ $msg }}
        </div>
    @endif
    @if($msg = session("message:error"))
        <div class="error">
            {{ $msg }}
        </div>
    @endif
    @if($msg = session("message:info"))
        <div class="info">
            {{ $msg }}
        </div>
    @endif
</div>