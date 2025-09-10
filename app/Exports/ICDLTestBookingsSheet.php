<?php

namespace App\Exports;

use App\Models\TrainingCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ICDLTestBookingsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
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
        return $this->trainingCenter->icdlTestBookings()
            ->with(['user', 'icdlTest'])
            ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ICDL Test Bookings';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Test Type',
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
            $row->icdlTest->name,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->payment_status,
            $row->booking_status,
            $row->total_price,
        ];
    }
}