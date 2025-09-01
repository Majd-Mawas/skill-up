<?php

namespace Database\Factories;

use App\Models\Hall;
use App\Models\User;
use App\Models\HallBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

class HallBookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HallBooking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = clone $startDate;
        $endDate->modify('+' . $this->faker->numberBetween(1, 5) . ' days');
        
        $startTime = $this->faker->dateTimeBetween('08:00', '18:00');
        $endTime = clone $startTime;
        $endTime->modify('+' . $this->faker->numberBetween(1, 4) . ' hours');
        
        $hall = Hall::inRandomOrder()->first() ?? Hall::factory()->create();
        $pricePerHour = $hall->price_per_hour;
        $hours = $endTime->diff($startTime)->h;
        $days = $endDate->diff($startDate)->days + 1;
        $totalPrice = $pricePerHour * $hours * $days;
        
        return [
            'hall_id' => $hall->id,
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'total_price' => $totalPrice,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }

    /**
     * Indicate that the booking is confirmed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }

    /**
     * Indicate that the booking is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Indicate that the booking is cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
            ];
        });
    }
}