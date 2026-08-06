<?php

namespace Tests\Feature\Customer;

use App\Models\Package;
use App\Models\User;
use App\Models\Notification;
use App\Livewire\Customer\Cart;
use App\Livewire\Customer\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Package $packageA;
    protected Package $packageB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $this->packageA = Package::create([
            'name' => 'Paket Sembako A',
            'price' => 1000000,
            'description' => 'Description A',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);

        $this->packageB = Package::create([
            'name' => 'Paket Sembako B',
            'price' => 2000000,
            'description' => 'Description B',
            'status' => 'active',
            'duration_weeks' => 40,
        ]);
    }

    public function test_cart_page_can_be_rendered_for_customer()
    {
        $response = $this->actingAs($this->customer)->get(route('customer.cart'));
        $response->assertStatus(200);
    }

    public function test_cart_page_redirects_for_guest()
    {
        $response = $this->get(route('customer.cart'));
        $response->assertRedirect(route('login'));
    }

    public function test_add_to_cart_via_catalog()
    {
        Livewire::actingAs($this->customer)
            ->test(Catalog::class)
            ->call('addToCart', $this->packageA->id);

        $cart = session('cart');
        $this->assertArrayHasKey($this->packageA->id, $cart);
        $this->assertEquals(1, $cart[$this->packageA->id]);
    }

    public function test_update_quantity_in_cart()
    {
        session(['cart' => [$this->packageA->id => 1]]);

        Livewire::actingAs($this->customer)
            ->test(Cart::class)
            ->call('updateQuantity', $this->packageA->id, 1); // quantity becomes 2

        $cart = session('cart');
        $this->assertEquals(2, $cart[$this->packageA->id]);

        Livewire::actingAs($this->customer)
            ->test(Cart::class)
            ->call('updateQuantity', $this->packageA->id, -1); // quantity becomes 1

        $cart = session('cart');
        $this->assertEquals(1, $cart[$this->packageA->id]);
    }

    public function test_remove_item_from_cart()
    {
        session(['cart' => [$this->packageA->id => 2, $this->packageB->id => 1]]);

        Livewire::actingAs($this->customer)
            ->test(Cart::class)
            ->call('removeItem', $this->packageA->id);

        $cart = session('cart');
        $this->assertArrayNotHasKey($this->packageA->id, $cart);
        $this->assertArrayHasKey($this->packageB->id, $cart);
    }

    public function test_confirm_checkout_registers_multiple_packages()
    {
        session(['cart' => [
            $this->packageA->id => 2,
            $this->packageB->id => 1,
        ]]);

        $this->assertEquals(0, $this->customer->packages()->count());

        Livewire::actingAs($this->customer)
            ->test(Cart::class)
            ->call('confirmCheckout');

        // Check if packages are attached in pivot table
        $this->customer->load('packages');
        $this->assertEquals(3, $this->customer->packages()->count());
        $this->assertEquals(2, $this->customer->packages()->where('package_id', $this->packageA->id)->count());
        $this->assertEquals(1, $this->customer->packages()->where('package_id', $this->packageB->id)->count());

        // Check session cart is cleared
        $this->assertNull(session('cart'));

        // Check in-app notification created
        $notification = Notification::where('user_id', $this->customer->id)->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Pendaftaran Paket Berhasil 🎉', $notification->title);
    }
}
