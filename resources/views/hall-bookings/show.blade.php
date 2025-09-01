@extends('layouts.vertical', ['title' => 'Hall Booking Details', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    @include('layouts.shared/page-title', ['sub_title' => 'Hall Bookings', 'page_title' => 'Booking Details'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Hall Booking #{{ $hallBooking->id }}</h4>
                        <div>
                            <a href="{{ route('hall-bookings.index') }}" class="btn btn-secondary me-1">Back to List</a>
                            <a href="{{ route('hall-bookings.edit', $hallBooking->id) }}" class="btn btn-primary me-1">Edit</a>
                            @if ($hallBooking->status == 'pending')
                                <form action="{{ route('hall-bookings.confirm', $hallBooking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success me-1">Confirm</button>
                                </form>
                                <form action="{{ route('hall-bookings.cancel', $hallBooking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5>Booking Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if ($hallBooking->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif ($hallBooking->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($hallBooking->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td>{{ $hallBooking->start_date->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date</th>
                                            <td>{{ $hallBooking->end_date->format('Y-m-d') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Start Time</th>
                                            <td>{{ \Carbon\Carbon::parse($hallBooking->start_time)->format('h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Time</th>
                                            <td>{{ \Carbon\Carbon::parse($hallBooking->end_time)->format('h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Price</th>
                                            <td>{{ number_format($hallBooking->total_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $hallBooking->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $hallBooking->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5>Hall Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Hall Name</th>
                                            <td>{{ $hallBooking->hall->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Capacity</th>
                                            <td>{{ $hallBooking->hall->capacity }} people</td>
                                        </tr>
                                        <tr>
                                            <th>Price Per Hour</th>
                                            <td>{{ number_format($hallBooking->hall->price_per_hour, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Training Center</th>
                                            <td>{{ $hallBooking->hall->trainingCenter->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5>User Information</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $hallBooking->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $hallBooking->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $hallBooking->user->phone_number }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection