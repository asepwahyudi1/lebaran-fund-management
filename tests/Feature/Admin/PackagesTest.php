<?php

namespace Tests\Feature\Admin;

use App\Models\Package;
use App\Models\User;
use App\Livewire\Admin\Packages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PackagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_package_and_propagate_duration_to_enrolled_users()
    {
        // 1. Create admin and customer users
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        // 2. Create an active package
        $package = Package::create([
            'name' => 'Paket Sembako A',
            'price' => 1200000,
            'description' => 'Paket A Deskripsi',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);

        // 3. Enroll the customer in the package (start with 40 weeks)
        $customer->packages()->attach($package->id, [
            'start_date' => now()->format('Y-m-d'),
            'duration_weeks' => $package->duration_weeks,
        ]);

        // Verify initial enrollment duration is 40 weeks
        $this->assertEquals(40, $customer->packages()->first()->pivot->duration_weeks);
        $this->assertEquals(30000, $customer->packages()->first()->pivot->duration_weeks ? ($package->price / 40) : 0);

        // 4. Act as the admin and update package duration to 45 weeks
        Livewire::actingAs($admin)
            ->test(Packages::class)
            ->set('editingId', $package->id)
            ->set('isEditing', true)
            ->set('name', 'Paket Sembako A Baru')
            ->set('price', 1200000)
            ->set('description', 'Deskripsi baru')
            ->set('status', 'active')
            ->set('duration_weeks', 45)
            ->call('save');

        // 5. Assert: Package configuration updated in packages table
        $package->refresh();
        $this->assertEquals(45, $package->duration_weeks);

        // 6. Assert: Pivot table entry updated for existing customer
        $pivotEntry = $customer->packages()->first()->pivot;
        $this->assertEquals(45, $pivotEntry->duration_weeks);

        // 7. Assert: Customer's calendar dynamic calculations updated
        $calendar = $customer->getInstallmentCalendar();
        $this->assertCount(45, $calendar); // Should now generate 45 week cards!
        $this->assertEquals(26667, (int) round($calendar[0]['expected_amount'])); // 1.200.000 / 45 = ~26.667 per week
    }
}
