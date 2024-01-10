<?php
class Product {
    public $id;
    public $name;
    public $description;
    public $price;
    public $quantity;

    public function getDetails() {
        return "$this->id / $this->name / $this->description / $this->price / $this->quantity";
    }

    public function updateQuantity($newQuantity) {
        $this->quantity = $newQuantity;
        return $this->quantity;
    }
}

class DigitalProduct extends Product {
    public $downloadLink;
}

class PhysicalProduct extends Product {
    public $weight;
    public $size;
}

class Cart {
    public $userId;
    public $items = [];

    public function addItem(Product $product) {
        $this->items[] = $product;
        return $this->items;
    }

    public function removeProduct(Product $product) {
        $this->items = array_values(array_filter($this->items, function($productId) use ($product) {
            return $productId->id !== $product->id;
        }));
    }

    public function calculateTotalPrice() {
        return array_reduce($this->items, function($total, $product) {
            return $total + $product->price;
        }, 0);
    }
}

class Review {
    public $id;
    public $productId;
    public $userId;
    public $comment;
    public $rating;

    public function publishReview() {
        return "№$this->id Продукта №$this->productId \n$this->comment\nРейтинг: $this->rating";
    }

    public function editComment($newComment) {
        $this->comment = $newComment;
        return $this->comment;
    }
}

class User {
    public $id;
    public $username;
    public $password;
    public $email;

    public function signIn($username, $password) {
        return ($username == $this->username && $password == $this->password) ? 1 : 0;
    }

    public function signOut() {
        $this->id = "";
        $this->username = "";
        $this->password = "";
        $this->email = "";
    }

    public function signUp($id, $username, $password, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
}

class FeedbackForm {
    public $id;
    public $user_id;
    public $message;

    public function submit() {
        return "Form Submitted: $this->id\n$this->user_id\n$this->message\n";
    }
}

