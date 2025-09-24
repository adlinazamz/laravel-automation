<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;
//use Illuminate\Support\Facades\Auth;


class ProfileApiController extends Controller
{
    /**
     * Display the user's profile form.
     */
    #[OA\Get(
        path: "/api/profile",
        summary: "Get user profile",
        operationId: "getProfile",
        tags: ["Profile"],
        description: "Returns the authenticated user's profile information.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                   ref: "#/components/schemas/Profile"
                )
            )
        ]
    )]
    public function edit(Request $request)
    {
        return response() ->json($request->user());
    }

    /**
 * Update the user's profile information.
 */
#[OA\Patch(
    path: "/api/profile",
    summary: "Update user profile",
    operationId: "updateProfile",
    tags: ["Profile"],
    requestBody: new OA\RequestBody(
        required: true,
        content: [
            "application/json" => new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["name", "email"],
                    properties: [
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "email", type: "string", format: "email")
                    ]
                )
            )
        ]
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Profile updated successfully",
            content: new OA\JsonContent(ref: "#/components/schemas/Profile")
        )
    ]
)]

    public function update(Request $request)
    {
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        return response()->json($request->user());
    }

    /**
     * Delete the user's account.
     */
    #[OA\Delete(
        path: "/api/profile",
        summary: "Delete user",
        operationId : "deleteProfile",
        tags:["Profile"],
        //parameters:[new OA\Parameter(name:"profile", in:"path", required:true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response:200, description: "Profile deleted successfully")]
    )]
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        //Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' =>'Profile deleted successfully.'], 200);
    }
}

