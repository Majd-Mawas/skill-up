<?php

namespace App\Exports;

use App\Models\TrainingCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlacementTestBookingsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $trainingCenter;

    public function __construct(TrainingCenter $trainingCenter)
    {
        $this->trainingCenter = $trainingCenter;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->trainingCenter->placementTestBookings()
            ->with(['user', 'placementTest'])
            ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Placement Test Bookings';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Test Name',
            'Booking Date',
            'Payment Status',
            'Booking Status',
            'Total Price',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->user->name,
            $row->placementTest->name,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->payment_status,
            $row->booking_status,
            $row->total_price,
        ];
    }
}