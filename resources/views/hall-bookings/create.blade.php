@extends('layouts.vertical', ['title' => 'Create Hall Booking', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    @include('layouts.shared/page-title', ['sub_title' => 'Hall Bookings', 'page_title' => 'Create Booking'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Create New Hall Booking</h4>
                        <a href="{{ route('hall-bookings.index') }}" class="btn btn-secondary">Back to List</a>
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

                    <form action="{{ route('hall-bookings.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hall_id" class="form-label">Hall</label>
                                    <select class="form-select" id="hall_id" name="hall_id" required>
                                        <option value="">Select Hall</option>
                                        @foreach ($halls as $hall)
                                            <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                                                {{ $hall->name }} - {{ $hall->trainingCenter->name }} ({{ number_format($hall->price_per_hour, 2) }}/hour)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price Calculation</label>
                                    <div class="alert alert-info" id="price-info">
                                        Select a hall and booking times to see the price calculation.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Create Booking</button>
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
                priceInfo.textContent = 'Please fill in all fields to calculate the price.';
                return;
            }

            // Get the selected hall option text to extract the price
            const selectedOption = hallSelect.options[hallSelect.selectedIndex];
            const optionText = selectedOption.textContent;
            const priceMatch = optionText.match(/(\d+(\.\d+)?)\/hour/);
            
            if (!priceMatch) {
                priceInfo.textContent = 'Could not determine the hall price.';
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
                priceInfo.textContent = 'End time must be after start time.';
                return;
            }
            
            const totalPrice = pricePerHour * totalHours * daysDiff;
            
            priceInfo.innerHTML = `
                <strong>Price Calculation:</strong><br>
                Hall Price: $${pricePerHour.toFixed(2)}/hour<br>
                Duration: ${totalHours.toFixed(2)} hours per day<br>
                Days: ${daysDiff} day(s)<br>
                <strong>Total Price: $${totalPrice.toFixed(2)}</strong>
            `;
        };

        // Add event listeners to all inputs
        hallSelect.addEventListener('change', calculatePrice);
        startDateInput.addEventListener('change', calculatePrice);
        endDateInput.addEventListener('change', calculatePrice);
        startTimeInput.addEventListener('change', calculatePrice);
        endTimeInput.addEventListener('change', calculatePrice);

        // Set default dates if empty
        if (!startDateInput.value) {
            const today = new Date();
            startDateInput.value = today.toISOString().split('T')[0];
        }
        
        if (!endDateInput.value) {
            const today = new Date();
            endDateInput.value = today.toISOString().split('T')[0];
        }
    });
</script>
@endsection