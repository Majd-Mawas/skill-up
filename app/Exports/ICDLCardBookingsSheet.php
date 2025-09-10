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
            'Personal Photo',
            'ID Front Photo',
            'ID Back Photo',
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Get individual media files URLs
        $personalPhotoUrl = $row->getMedia('personal_photo')->first() ? 
            $row->getMedia('personal_photo')->first()->getFullUrl() : '';
            
        $idFrontPhotoUrl = $row->getMedia('id_front_photo')->first() ? 
            $row->getMedia('id_front_photo')->first()->getFullUrl() : '';
            
        $idBackPhotoUrl = $row->getMedia('id_back_photo')->first() ? 
            $row->getMedia('id_back_photo')->first()->getFullUrl() : '';

        return [
            $row->id,
            $row->user->name,
            $row->full_name_en,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->payment_status,
            $row->booking_status,
            $row->total_price,
            $personalPhotoUrl,
            $idFrontPhotoUrl,
            $idBackPhotoUrl,
        ];
    }
}