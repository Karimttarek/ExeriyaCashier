@if (session('status'))
@push('script')
    <script>
        window.addEventListener("load", (event) => {
            document.getElementById('event').click();
        });
    </script>
@endpush
@endif
@if (session('error'))
@push('script')
    <script>
        window.addEventListener("load", (event) => {
            document.getElementById('errEvent').click();
        });
    </script>
@endpush
@endif
@if (session('warning'))
@push('script')
    <script>
        window.addEventListener("load", (event) => {
            document.getElementById('warningEvent').click();
        });
    </script>
@endpush
@endif


<button class="hidden" id="event" onclick="window.$wireui.notify({
    title: '{{__('app.SUCCESS')}}',
    description: '{{session('status')}}',
    icon: 'success'})">
</button>
<button class="hidden" id="errEvent" onclick="window.$wireui.notify({
    title: '{{__('app.ERROR')}}',
    description: '{{session('error')}}',
    icon: 'error'})">
</button>
<button class="hidden" id="warningEvent" onclick="window.$wireui.notify({
    title: '{{__('app.Warning')}}',
    description: '{{session('warning')}}',
    icon: 'warning'})">
</button>
