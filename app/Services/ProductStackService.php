<?php

namespace App\Services;

class ProductStackService
{
	/**
	 *  Define the list off product select by user
	 * 
	 * @var array
	 */
	private $products = [];

	/**
	 * ProductStackService constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->products = session('products', []);
	}

	/**
	 * Push product 
	 * 
	 * @param array $product
	 */
    public function push(array $product)
    {
    	$this->products[$product['id']] = $product;

    	session()->put('products', $this->products);
    }

    /**
     * Remove product by id
     * 
     * @param int $id
     */
    public function remove($id)
    {
    	$removed = false;

    	foreach ($this->products as $product_id => $product) {
    		if ($product_id == $id) {
    			$removed = true;
	    		unset($this->products[$id]);
	    		break;
    		}
    	}

    	if ($removed) {
	    	session()->put('products', $this->products);
    	}

    	return $removed;
    }

    /**
     * Compute the quantity of all stack items
     * 
     * @return int
     */
    public function quantity()
    {
    	$quantity = 0;
    	
    	foreach($this->products as $product) {
    		$quantity += $product['quantity'];
    	}

    	return quantity;
    }

    /**
     * Compute the price of all stack items
     * 
     * @return int
     */
    public function price()
    {
    	$price = 0;
    	
    	foreach($this->products as $product) {
    		$price += $product['price'];
    	}

    	return $price;
    }

    /**
     * Count of items
     * 
     * @return bool
     */
    public function count()
    {
    	return count($this->products);
    }

    /**
     * Check if product exists
     * 
     * @param int $id
     * @return bool
     */
    public function exists($id)
    {
    	return isset($this->products[$id]);
    }

    /**
     * Get the products to array
     * 
     * @return array
     */
    public function toArray()
    {
    	return $this->products;
    }

    /**
     * Get the products to model
     * 
     * @return \Bow\Database\Barry\Collection
     */
    public function getProducts()
    {
    	$ids = [];

    	foreach($this->products as $product) {
    		$ids[] = $product['id'];
    	}

        if (count($ids) == 0) {
            return [];
        }

        $products = \App\Models\Product::whereIn('id', $ids)->get();
        $data = [];

        foreach($products as $product) {
            $data[] = [
                'model' => $product,
                'quantity' => $this->products[$product->id]['quantity']
            ];
        }

    	return $data;
    }
}
