<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use OpenApi\Attributes as OA;

class AuthController extends BaseController
{
    /**
     * Register a User.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: "/api/auth/register",
        summary: "Register user account",
        operationId: "registerUser",
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
    public function register (Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'email'=> 'required|email',
            'password'=> 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if($validator-> fails()){
            return $this ->sendError ('Validation Error.',$validator ->errors());
        }

        $input =$request -> all();
        $input['password'] =bcrypt ($input ['password']);
        $user = User::create($input);
        $success['user'] = $user;

        return $this ->sendResponse($success,'User register successfully.' );
    }
    /**
     * Get a JWT via given credentials.
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
    path: "/api/auth/login",
    summary: "Login account",
    operationId: "logInUser",
    tags: ["Auth"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["email", "password"],
            properties: [
                new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                new OA\Property(property: "password", type: "string", example: "password123"),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Successful login",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "access_token", type: "string", example: "eyJ0eXAiOiJKV1Qi..."),
                    new OA\Property(property: "token_type", type: "string", example: "bearer"),
                    new OA\Property(property: "expires_in", type: "integer", example: 3600)
                ]
            )
        ),
        new OA\Response(response: 401, description: "Invalid credentials"),
        new OA\Response(response: 422, description: "Validation error")
    ]
)]

    public function login(){
        $credentials = request(['email','password']);
        $token = auth('api')->attempt($credentials);
        if (!$token){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $success = $this ->respondWithToken ($token);
        return $this ->sendResponse($success, 'User login successfully.');
    }
    /**
     * Get the authenticated user.
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: "/api/auth/profile",
        summary: "Load user profile",
        security:[["bearerAuth"=>[]]],
        operationId: "profileUser",
        tags: ["Auth"],
        responses: [
        new OA\Response(
            response: 200,
            description: "Profile Load Successfully",
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Load User Profile")
                        ]
                    )
                )
            ]
        )
    ]
)]
    public function profile(){
        $success = auth('api')->user();
        return $this->sendResponse($success,'Load User Profile.'); 
    }
    /**
     * Log the user out (Invalidate the token).
     * @return \Illuminate\Http\JsonResponse
     * @method string refresh()
     */
    #[OA\Post(
        path: "/api/auth/logout",
        summary: "Logout account",
        security:[["bearerAuth"=>[]]],
        operationId: "logOutUser",
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

public function logout()
{
    auth('api')->logout();
    return $this->sendResponse([], 'User logged out successfully.');
}

    #[OA\Post(
        path: "/api/auth/refresh",
        summary: "Refresh JWT",
        security:[["bearerAuth"=>[]]],
        operationId: "refreshJWT",
        tags: ["Auth"],
        responses: [
        new OA\Response(
            response: 200,
            description: "Successful token refresh generation",
            content: [
                "application/json" => new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Token refreshed successfully")
                        ]
                    )
                )
            ]
        )
    ]
)]
    public function refresh(){
        $success = $this->respondWithToken(auth('api')->refresh());
        return $this ->sendResponse($success, 'Token refreshed successfully.');
    }
    /**
     * Get the token array structure.
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     * @method \Tymon\JWTAuth\JWTGuard $auth
     */
    protected function RespondWithToken($token){
        return[
            'access_token' => $token,
            'token_type' =>'bearer',
            'expires_in' =>auth('api')->factory()->getTTL()*60
        ];
    }
}
