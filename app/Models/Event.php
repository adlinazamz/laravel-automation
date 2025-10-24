<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Event",
    title: "Event",
    required: ["name", "description", "date_start", "date_end"],
    // temp change to dynamic ["name"],
    properties: [
        new OA\Property(property: "name", type:"string", example: "Example"), new OA\Property(property: "description", type:"string", example: "Example"), new OA\Property(property: "date_start", type:"string", format:"date", example: "2025-01-01"), new OA\Property(property: "date_end", type:"string", format:"date", example: "2025-01-01")
    ]
)]

class Event extends Model{
    use HasFactory;

    protected $fillable = [
        // temp get rid of static 'name',
        'id', 'name', 'description', 'date_start', 'date_end', 'created_at', 'updated_at'
    ];
    protected $dates = ['created_at', 'updated_at'];
}