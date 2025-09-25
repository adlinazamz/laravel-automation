<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class NewPasswordApiController extends Controller
{
   /**
     * Display the password reset view.
     */
    //post
    #[OA\Get(
        path: "/api/reset-password/{token}",
        summary: "Show password reset token info",
        operationId: "showResetPasswordForm",
        tags: ["Auth"],
        parameters: [new OA\Parameter(name:"token", in: "path", required: true, schema: new OA\Schema(type: "string"))],
        responses: [new OA\Response(response : 200, description: "Token info returned")]
    )]

    public function create(Request $request, $token)
    {
        return response()->json([
            'message' => 'Provide email and new password to reset.',
            'token' => $token
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    #[OA\Post(
        path:"/api/reset-password",
        summary: "Reset password using token",
        operationId: "resetPassword",
        tags:["Auth"],
        requestBody: new OA\RequestBody(
            required: true, 
            content: [
                "application/json"=> new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["token", "email","password", "password_confirmation"],
                        properties:[new OA\property(property: "token", type: "string", example:"abcd1234"),
                                    new OA\property(property: "email", type:"string", format: "email", example:"user@example.com"),
                                    new OA\property(property: "password", type: "string", format:"password" , example:"password123"),
                                    new OA\property(property: "password_confirmation", type:"string", format: "password", example:"newpassword123")
                                    ]
                    )
                )
            ]
                    ), 
                    responses: [
                        new OA\Response (response: 200, description: "password updated successfully"),
                        new OA\Response(response: 422, description: "Validation error"),
                    ]
    )]

   public function store(Request $request)
{
    Log::info('Reset password store called', $request->all());

    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json(['message' => 'Password updated successfully.'], 200);
    }

    return response()->json(['message' => __($status)], 422);
}
}