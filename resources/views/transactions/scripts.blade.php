@section('scripts')
    <script type="text/template" id="item-options-template">
    @foreach ($items as $item)
        <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
    @endforeach
</script>
    <script src="{{ asset('js/transactions/transactions.js') }}"></script>
    <script src="{{ asset('js/transactions/reserve.js') }}"></script>
    <script src="{{ asset('js/transactions/actions.js') }}"></script>
    <script src="{{ asset('js/transactions/forms.js') }}"></script>
    <script src="{{ asset('js/transactions/return.js') }}"></script>
    <script type="text/javascript">
        // Ensure jQuery and dependencies are loaded
        $(document).ready(function() {
            // Initialize Chosen for all select elements
            $("select").chosen({
                width: "100%"
            });
        });
    </script>
@endsection
