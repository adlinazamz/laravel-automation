<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use OpenApi\Attributes as OA;

class RegisteredUserApiController extends Controller
{
    #[OA\Post(
        path: "/api/register",
        summary: "Register user account",
        operationId: "registeruser",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "email", "password", "password_confirmation"],
                        properties: [
                            new OA\Property(property: "name", type: "string", example: "user"),
                            new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                            new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                            new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123")
                        ]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "User registered successfully",
                content: [
                    "application/json" => new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            type: "object",
                            properties: [
                                new OA\Property(property: "message", type: "string", example: "User registered successfully")
                            ]
                        )
                    )
                ]
                            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content:["application/json"=> new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type:"object",
                        properties:[
                            new OA\Property(property: "message", type: "string", example:"Validation failed"),
                            new OA\Property(
                                property:"errors", 
                                type:"object", 
                            )
                        ] 
                    )
                )]
            )
        ]
    )]
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return response()->json([
            'message' => 'User registered successfully', 'user' => $user], 201);
    }
}