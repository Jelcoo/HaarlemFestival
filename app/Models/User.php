<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enum\UserRoleEnum;

class User
{
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public UserRoleEnum $role;
    public ?string $address;
    public ?string $city;
    public ?string $postal_code;
    public ?string $stripe_customer_id;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->firstname = $collection['firstname'];
        $this->lastname = $collection['lastname'];
        $this->email = $collection['email'];
        $this->password = $collection['password'];
        $this->role = UserRoleEnum::from($collection['role']);
        $this->address = $collection['address'];
        $this->city = $collection['city'];
        $this->postal_code = $collection['postal_code'];
        $this->stripe_customer_id = $collection['stripe_customer_id'];
        $this->created_at = Carbon::parse($collection['created_at']);
    }
}
