@component('mail::message')
    # Trade Triggered

    @component('mail::table')
        |        |          |
        | ------------- |:-------------:|
        | Symbol     | {{ $record->symbol }}  |
        | Action     | {{ $record->action }}  |
        | Price     | {{ $record->price }}  |
        | Cost     | {{ $record->cost }}  |
        | Qty     | {{ $record->quantity }}  |
        | Fee     | {{ $record->fee }}  |
        | Trade At     | {{ \Carbon\Carbon::parse($record->order_created_at)->toDateString() }}  |
    @endcomponent

@endcomponent
