<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

//Swagger Annotations for documentations
#[OA\Info(
    version: "1.0.0",
    title: "Product API",
    description: "API documentation for the Product application"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local API server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",)]

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
