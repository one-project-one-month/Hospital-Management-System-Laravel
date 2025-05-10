<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicineNames = [
            'Paracetamol', 'Ibuprofen', 'Amoxicillin', 'Metformin', 'Atorvastatin',
            'Amlodipine', 'Omeprazole', 'Losartan', 'Azithromycin', 'Ciprofloxacin',
            'Cetirizine', 'Doxycycline', 'Pantoprazole', 'Hydrochlorothiazide',
            'Prednisone', 'Simvastatin', 'Levothyroxine', 'Lisinopril', 'Gabapentin', 'Alprazolam',
        ];

        return [
            'name' => $this->faker->randomElement($medicineNames),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
