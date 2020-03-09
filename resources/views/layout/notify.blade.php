@if (Session::has('success'))
    <div class="notify" id="notify-success" data="{{ Session::get('success') }}">
    </div>
@elseif (Session::has('unccess'))
    <div class="notify" id="notify-unsuccess" data="{{ Session::get('unccess') }}">
    </div>
@endif
