<?php

namespace App\Exports;

use App\Models\TrainingCenter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class TrainingCenterBookingsExport implements WithMultipleSheets
{
    protected $trainingCenter;

    public function __construct(TrainingCenter $trainingCenter)
    {
        $this->trainingCenter = $trainingCenter;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        // Course Bookings Sheet
        $sheets[] = new CourseBookingsSheet($this->trainingCenter);

        // Placement Test Bookings Sheet
        $sheets[] = new PlacementTestBookingsSheet($this->trainingCenter);

        // ICDL Test Bookings Sheet
        $sheets[] = new ICDLTestBookingsSheet($this->trainingCenter);

        // ICDL Card Bookings Sheet
        $sheets[] = new ICDLCardBookingsSheet($this->trainingCenter);

        return $sheets;
    }
}