<?php

namespace App\Exports;

use App\Models\TrainingCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ICDLCardBookingsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
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
        return $this->trainingCenter->icdlCardBookings()
            ->with(['user', 'icdlCard', 'media'])
            ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ICDL Card Bookings';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Full Name in English',
            'Booking Date',
            'Payment Status',
            'Booking Status',
            'Total Price',
            'Media Files',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Get media files URLs
        $mediaUrls = $row->getMedia('attachments')->map(function($media) {
            return $media->getFullUrl();
        })->implode(", ");

        return [
            $row->id,
            $row->user->name,
            $row->full_name_en,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->payment_status,
            $row->booking_status,
            $row->total_price,
            $mediaUrls,
        ];
    }
}