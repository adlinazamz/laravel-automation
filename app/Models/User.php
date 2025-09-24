<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Profile",
    title: "Profile",
    required: ["name", "email"],
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "Unique identifier for the user", example: 1),
        new OA\Property(property: "name", type: "string", description: "Name of the user", example: "user"),
        new OA\Property(property: "email", type: "string", description: "User Email", example: "user@email.com"),
        new OA\Property(property: "email_verified_at", type: "string", format: "date-string",nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time", description: "Timestamp when the product was created", example: "2023-10-02T12:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Timestamp when the product was last updated", example: "2023-10-02T12:00:00Z"),
    ]
)]

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
