@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Orders</h2>
    <form action="{{ route('orders.download-pdf') }}" method="POST">
        @csrf
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Download Selected Orders</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}"></td>
                        <td>{{ $order->id }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>

<script>
document.getElementById('select-all').addEventListener('change', function() {
    var checkboxes = document.getElementsByName('order_ids[]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});
</script>
@endsection
