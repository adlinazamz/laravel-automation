<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Illuminate\Validation\Rules\Password;

class PasswordApiController extends Controller
{
    /**
     * Update the user's password.
     */

    #[OA\Put(
        path: "/api/password/update",
        summary: "Update user password",
        operationId: "updatePassword",
        tags: ["Auth"],
        parameters: [new OA\Parameter(name: "current-password", in: "path", required:"true", schema:new OA\Schema (type: "string")),
                    new OA\Parameter(name: "new_password", in: "path", required:"true", schema:new OA\Schema (type:"string")),
                    new OA\Parameter(name: "new_password_confirmation", in:"path", required: "true", schema:new OA\Schema(type:"string"))
    ],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "application/json" => new OA\MediaType(
                    mediaType:"application/json",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["current_password", "password", "password_confirmation"],
                        properties: [
                            new OA\Property(property: "current_password", type: "string", example:"oldpassword123"),
                            new OA\Property(property: "new_password", type: "string", example:"newpassword123"),
                            new OA\Property(property: "new_password_confirmation", type: "string", example:"newpassword123")
                       
                        ]
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Password updated successfully"),
                    new OA\Response(response: 422, description: "Validation Error"),
                    new OA\Response(response: 401, description: "Unauthorized")
        ]
    )]

    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required' , 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' =>'Password updated successfully.'], 200);

    }
}

