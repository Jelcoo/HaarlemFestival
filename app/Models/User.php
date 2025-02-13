<?php

namespace App\Models;

use App\Enum\UserRoleEnum;
use Carbon\Carbon;

class User
{
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public UserRoleEnum $role;
    public string $address;
    public string $city;
    public string $postal_code;
    public string $profile_picture;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection["id"];
        $this->firstname = $collection["firstname"];
        $this->lastname = $collection["lastname"];
        $this->email = $collection["email"];
        $this->password = $collection["password"];
        $this->role = UserRoleEnum::from($collection["role"]);
        $this->address = $collection["address"];
        $this->city = $collection["city"];
        $this->postal_code = $collection["postal_code"];
        $this->profile_picture = $collection["profile_picture"];
        $this->created_at = Carbon::parse($collection["created_at"]);
    }
}
