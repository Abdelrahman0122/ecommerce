<?php

namespace App\Livewire;

use App\Helpers\CartManagment;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - BuyEasy')]
class CartPage extends Component
{
    public $cart_items = [];
    public $total;
    public function mount()
    {
        $this->cart_items = CartManagment::getCartItems();
        $this->total = CartManagment::calculateCartTotal($this->cart_items);
    }

    public function removeFromCart($product_id)
    {

        $this->cart_items =  CartManagment::removeItemFromCart($product_id);
        $this->total = CartManagment::calculateCartTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagment::incrementItemQuantity($product_id);
        $this->total = CartManagment::calculateCartTotal($this->cart_items);
    }
    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagment::decrementItemQuantity($product_id);
        $this->total = CartManagment::calculateCartTotal($this->cart_items);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
