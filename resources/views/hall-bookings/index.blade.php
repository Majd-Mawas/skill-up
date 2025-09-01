@extends('layouts.vertical', ['title' => 'Hall Bookings', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    @include('layouts.shared/page-title', ['sub_title' => 'Hall Bookings', 'page_title' => 'Hall Bookings'])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Hall Bookings</h4>
                        <a href="{{ route('hall-bookings.create') }}" class="btn btn-primary">Create New Booking</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hall</th>
                                    <th>User</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hallBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->hall->name }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $booking->end_date->format('Y-m-d') }}</td>
                                        <td>{{ number_format($booking->total_price, 2) }}</td>
                                        <td>
                                            @if ($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif ($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($booking->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('hall-bookings.show', $booking->id) }}" class="btn btn-sm btn-info me-1">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('hall-bookings.edit', $booking->id) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('hall-bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hall bookings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $hallBookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection