<?php
session_start();

// Initialize inventory if not set
if(!isset($_SESSION['inventory'])){
    $_SESSION['inventory'] = [
        ['name'=>'Laptop','price'=>100000,'image'=>'laptop.jpg','allowed_discount'=>10,'quantity'=>5],
        ['name'=>'Smartphone','price'=>50000,'image'=>'smartphone.jpg','allowed_discount'=>5,'quantity'=>10],
        ['name'=>'Headphones','price'=>5000,'image'=>'headphones.jpg','allowed_discount'=>15,'quantity'=>20]
    ];
}

// Get all inventory
function getInventory(){ return $_SESSION['inventory']; }

// Add a product
function addProduct($p){ $_SESSION['inventory'][] = $p; }

// Update a product by name
function updateProduct($name, $data){
    foreach($_SESSION['inventory'] as &$p){
        if($p['name'] === $name){
            $p = array_merge($p, $data);
            return true;
        }
    }
    return false;
}

// Delete a product by name
function deleteProduct($name){
    foreach($_SESSION['inventory'] as $k => $p){
        if($p['name'] === $name){
            unset($_SESSION['inventory'][$k]);
            $_SESSION['inventory'] = array_values($_SESSION['inventory']); // Reindex
            return true;
        }
    }
    return false;
}

// Update quantity of a product by name
function updateQuantity($name, $q){
    foreach($_SESSION['inventory'] as &$p){
        if($p['name'] === $name){
            $p['quantity'] = $q;
            return true;
        }
    }
    return false;
}

// Calculate total inventory value
function totalInventoryValue(){
    $t = 0;
    foreach($_SESSION['inventory'] as $p){
        $t += $p['price'] * (1 - $p['allowed_discount']/100) * $p['quantity'];
    }
    return $t;
}
?>
