<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagment
{
    // add item to the cart
    public static function addItemToCart($product_id)
    {
        $cartItems = self::getCartItems();
        $existing_item = null;
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }
        if ($existing_item !== null) {
            $cartItems[$existing_item]['quantity'] += 1;
            $cartItems[$existing_item]['total_amount'] = $cartItems[$existing_item]['quantity'] *
                $cartItems[$existing_item]['unit_amount'];
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if ($product) {
                $cartItems[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' => 1,
                    'total_amount' => $product->price,
                    'image' => $product->images[0]
                ];
            }
        }
        self::addCartItemstoCookie($cartItems);
        return count($cartItems);
    }

    public static function addItemToCartWithQty($product_id, $qty = 1)
    {
        $cartItems = self::getCartItems();
        $existing_item = null;
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item = $key;
                break;
            }
        }
        if ($existing_item !== null) {
            $cartItems[$existing_item]['quantity'] =  $qty;
            $cartItems[$existing_item]['total_amount'] = $cartItems[$existing_item]['quantity'] *
                $cartItems[$existing_item]['unit_amount'];
        } else {
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if ($product) {
                $cartItems[] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'unit_amount' => $product->price,
                    'quantity' =>  $qty,
                    'total_amount' => $product->price,
                    'image' => $product->images[0]
                ];
            }
        }
        self::addCartItemstoCookie($cartItems);
        return count($cartItems);
    }


    // remove item from the cart
    public static function removeItemFromCart($product_id)
    {
        $cartItems = self::getCartItems();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cartItems[$key]);
                break;
            }
        }
        self::addCartItemstoCookie($cartItems);
        return $cartItems;
    }

    // add cartItems to cookies
    public static function addCartItemstoCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30); // 30 days
    }

    // clear cartitems from cookie
    public static function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    //get all cart items from cookies
    public static function getCartItems()
    {
        $cartItems = json_decode(Cookie::get('cart_items'), true);
        if (!$cartItems) {
            $cartItems = [];
        }
        return $cartItems;
    }

    // increment item quantity
    public static function incrementItemQuantity($product_id)
    {
        $cartItems = self::getCartItems();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $cartItems[$key]['quantity'] += 1;
                $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                break;
            }
        }
        self::addCartItemstoCookie($cartItems);
        return $cartItems;
    }
    public static function decrementItemQuantity($product_id)
    {
        $cartItems = self::getCartItems();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $product_id) {
                if ($cartItems[$key]['quantity'] > 1) {
                    $cartItems[$key]['quantity'] -= 1;
                    $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                }
            }
        }
        self::addCartItemstoCookie($cartItems);
        return $cartItems;
    }
    public static function calculateCartTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}
