<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tester;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use OpenApi\Attributes as OA;


class TesterApiController extends Controller{
    
    #[OA\Get(
        path: "/api/tester",
        summary: "Get list of tester",
        operationId: "getTester",
        tags: ["Tester"],
        description: "Returns a paginated list of tester.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Tester")
                )
            )
        ]
    )]
    
    public function index(Request $request){
        $tester=Tester::all();
        return response()->json($tester);
    }

 #[OA\Post(
        path: "/api/tester",
        summary: "Create a new Tester",
        operationId: "createTester",
        tags: ["Tester"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                       /* need to be changed */
                        required: ["name"],
                        properties: [
                            new OA\Property(property: "name", type: "string")
                        ] 
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Tester created successfully")]
    )]
    public function store(Request $request){
        $request->validate(['name' => 'required']);
        $input = $request->all();
        $tester = Tester::create($input);
        
        return response()->json([
                'message' => 'Tester created successfully',
                'data' => $tester
            ], 200);    
    }

    #[OA\Get(
        path: "/api/tester/{tester}",
        summary: "Get a specific tester",
        operationId: "getTesterById",
        tags: ["Tester"],
        parameters: [new OA\Parameter(name: "tester", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Successful operation")]
    )]
    public function show(Tester $tester){
        return response()->json($tester);
    }

    #[OA\Post(
        path: "/api/tester/{tester}",
        summary: "Update a Tester by ID (POST + _method=PUT)",
        operationId: "updateTesterviaPost",
        tags: ["Tester"],
        parameters: [new OA\Parameter(name: "tester", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        /*change based on the table column*/
                        required: ["name", "_method"],
                        properties: [
                            new OA\Property(property: "_method", type: "string", example:"PUT"),
                            new OA\Property(property: "name", type: "string"),
                        ]
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Tester updated successfully")]
    )]    
    public function update(Request $request, Tester $tester){
        $request->validate(['name' => 'required']);
        $input = $request->all();
        $tester->update($input);
        return response()->json(['message' => 'Tester updated successfully.'], 200);
    }

    #[OA\Delete(
        path: "/api/tester/{tester}",
        summary: "Delete a tester by ID",
        operationId: "deleteTester",
        tags: ["Tester"],
        parameters: [new OA\Parameter(name: "tester", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Tester deleted successfully")]
    )]
    public function destroy($id){
        Tester::destroy($id);
        return response()->json(['message' => 'Tester deleted successfully.'], 200);
    }
}