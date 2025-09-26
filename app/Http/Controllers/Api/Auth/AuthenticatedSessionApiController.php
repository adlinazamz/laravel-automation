<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use OpenApi\Attributes as OA;


class AuthenticatedSessionApiController extends Controller
{
    
    /**
     * Handle an incoming authentication request.
     */
    #[OA\Post(
        path: "/api/login",
        summary: "Login account",
        operationId: "loginUser",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["email", "password"],
                        properties: [
                            new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                            new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                        ]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Successful login",
                content: [
                    "application/json" => new OA\MediaType(
                        mediaType: "application/json",
                        schema: new OA\Schema(
                            type: "object",
                            properties: [
                                new OA\Property(property: "message", type: "string", example: "User login successfully")
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
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        return response()->json([
            'message' => 'User login successfully', 'user' => Auth::user()], 201);
    }

    /**
     * Destroy an authenticated session.
     */
    #[OA\Post(
        path: "/api/logout",
        summary: "Logout account",
        operationId: "logoutUser",
        tags: ["Auth"],
        responses: [
        new OA\Response(
            response: 200,
            description: "Successful logout",
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "User logged out successfully")
                        ]
                    )
                )
            ]
        )
    ]
)]
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json([
            'message' => 'User logout successfully'], 201);
    }
}
