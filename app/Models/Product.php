<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Product",
    title: "Product",
    required: ["name", "detail", "image","updated_at"],
    properties: [
        new OA\Property(property: "id", type: "integer", format: "int64", description: "Unique identifier for the product", example: 1),
        new OA\Property(property: "name", type: "string", description: "Name of the product", example: "Book Title"),
        new OA\Property(property: "detail", type: "string", description: "Detailed description of the product", example: "This is a detailed description of the book."),
        new OA\Property(property: "image", type: "string", format: "url", description: "URL of the product image", example: "http://example.com/image.jpg"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", description: "Timestamp when the product was last updated", example: "2023-10-02T12:00:00Z"),
    ]
)]

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail',
        'image',
    ];
    protected $dates = ['created_at', 'updated_at'];
}
