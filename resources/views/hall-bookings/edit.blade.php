@extends('layouts.vertical', ['title' => 'Edit Hall Booking', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    @include('layouts.shared/page-title', ['sub_title' => 'Hall Bookings', 'page_title' => 'Edit Booking'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Edit Hall Booking #{{ $hallBooking->id }}</h4>
                        <div>
                            <a href="{{ route('hall-bookings.index') }}" class="btn btn-secondary me-1">Back to List</a>
                            <a href="{{ route('hall-bookings.show', $hallBooking->id) }}" class="btn btn-info">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hall-bookings.update', $hallBooking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hall_id" class="form-label">Hall</label>
                                    <select class="form-select" id="hall_id" name="hall_id" required>
                                        <option value="">Select Hall</option>
                                        @foreach ($halls as $hall)
                                            <option value="{{ $hall->id }}" {{ old('hall_id', $hallBooking->hall_id) == $hall->id ? 'selected' : '' }}>
                                                {{ $hall->name }} - {{ $hall->trainingCenter->name }} ({{ number_format($hall->price_per_hour, 2) }}/hour)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $hallBooking->start_date->format('Y-m-d')) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $hallBooking->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($hallBooking->start_time)->format('H:i')) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($hallBooking->end_time)->format('H:i')) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $hallBooking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status', $hallBooking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="cancelled" {{ old('status', $hallBooking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price Calculation</label>
                                    <div class="alert alert-info" id="price-info">
                                        Current Total Price: ${{ number_format($hallBooking->total_price, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hallSelect = document.getElementById('hall_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const priceInfo = document.getElementById('price-info');

        const calculatePrice = function() {
            const hallId = hallSelect.value;
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (!hallId || !startDate || !endDate || !startTime || !endTime) {
                priceInfo.innerHTML = 'Please fill in all fields to calculate the price.<br>Current Total Price: ${{ number_format($hallBooking->total_price, 2) }}';
                return;
            }

            // Get the selected hall option text to extract the price
            const selectedOption = hallSelect.options[hallSelect.selectedIndex];
            const optionText = selectedOption.textContent;
            const priceMatch = optionText.match(/(\d+(\.\d+)?)\/hour/);
            
            if (!priceMatch) {
                priceInfo.innerHTML = 'Could not determine the hall price.<br>Current Total Price: ${{ number_format($hallBooking->total_price, 2) }}';
                return;
            }

            const pricePerHour = parseFloat(priceMatch[1]);
            
            // Calculate days between dates
            const start = new Date(startDate);
            const end = new Date(endDate);
            const daysDiff = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
            
            // Calculate hours between times
            const [startHour, startMinute] = startTime.split(':').map(Number);
            const [endHour, endMinute] = endTime.split(':').map(Number);
            
            let hoursDiff = endHour - startHour;
            let minutesDiff = endMinute - startMinute;
            
            if (minutesDiff < 0) {
                hoursDiff -= 1;
                minutesDiff += 60;
            }
            
            const totalHours = hoursDiff + (minutesDiff / 60);
            
            if (totalHours <= 0) {
                priceInfo.innerHTML = 'End time must be after start time.<br>Current Total Price: ${{ number_format($hallBooking->total_price, 2) }}';
                return;
            }
            
            const totalPrice = pricePerHour * totalHours * daysDiff;
            
            priceInfo.innerHTML = `
                <strong>New Price Calculation:</strong><br>
                Hall Price: $${pricePerHour.toFixed(2)}/hour<br>
                Duration: ${totalHours.toFixed(2)} hours per day<br>
                Days: ${daysDiff} day(s)<br>
                <strong>New Total Price: $${totalPrice.toFixed(2)}</strong><br>
                <small>Current Total Price: ${{ number_format($hallBooking->total_price, 2) }}</small>
            `;
        };

        // Add event listeners to all inputs
        hallSelect.addEventListener('change', calculatePrice);
        startDateInput.addEventListener('change', calculatePrice);
        endDateInput.addEventListener('change', calculatePrice);
        startTimeInput.addEventListener('change', calculatePrice);
        endTimeInput.addEventListener('change', calculatePrice);

        // Calculate initial price
        calculatePrice();
    });
</script>
@endsection