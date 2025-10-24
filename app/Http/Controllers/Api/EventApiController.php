<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use OpenApi\Attributes as OA;


class EventApiController extends Controller{
    
    #[OA\Get(
        path: "/api/event",
        summary: "Get list of event",
        security:[["bearerAuth"=>[]]],
        operationId: "getEvent",
        tags: ["Event"],
        description: "Returns a paginated list of event.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Event")
                )
            )
        ]
    )]
    
    public function index(Request $request){
        $event=Event::all();
        return response()->json($event);
    }

 #[OA\Post(
        path: "/api/event",
        summary: "Create a new Event",
        security:[["bearerAuth"=>[]]],
        operationId: "createEvent",
        tags: ["Event"],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "description", "date_start", "date_end"],
                        properties: [
                            new OA\Property(property: "name", type:"string", example: "Example"), new OA\Property(property: "description", type:"string", example: "Example"), new OA\Property(property: "date_start", type:"string", format:"date", example: "2025-01-01"), new OA\Property(property: "date_end", type:"string", format:"date", example: "2025-01-01")
                        ] 
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Event created successfully")]
    )]
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
'description'=>'required|string|max:255'
        ]);
        $input = $request->all();
        $event = Event::create($input);
        
        return response()->json([
                'message' => 'Event created successfully',
                'data' => $event
            ], 200);    
    }

    #[OA\Get(
        path: "/api/event/{event}",
        summary: "Get a specific event",
        security:[["bearerAuth"=>[]]],
        operationId: "getEventById",
        tags: ["Event"],
        parameters: [new OA\Parameter(name: "event", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Successful operation")]
    )]
    public function show(Event $event){
        return response()->json($event);
    }

    #[OA\Post(
        path: "/api/event/{event}",
        summary: "Update a Event by ID (POST + _method=PUT)",
        security:[["bearerAuth"=>[]]],
        operationId: "updateEventviaPost",
        tags: ["Event"],
        parameters: [new OA\Parameter(name: "event", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "description", "date_start", "date_end"],
                        properties: [
                            new OA\Property(property: "name", type:"string", example: "Example"), new OA\Property(property: "description", type:"string", example: "Example"), new OA\Property(property: "date_start", type:"string", format:"date", example: "2025-01-01"), new OA\Property(property: "date_end", type:"string", format:"date", example: "2025-01-01"),
                            new OA\Property(property:"_method", type: "string", example:"PUT")
                        ] 
                    )
                )
            ]
        ),
        responses: [new OA\Response(response: 200, description: "Event updated successfully")]
    )]    
    public function update(Request $request, Event $event){
        $request->validate([
            'name'=>'required|string|max:255',
'description'=>'required|string|max:255'
        ]);
        $input = $request->all();
        $event->update($input);
        return response()->json(['message' => 'Event updated successfully.'], 200);
    }

    #[OA\Delete(
        path: "/api/event/{event}",
        summary: "Delete a event by ID",
        security:[["bearerAuth"=>[]]],
        operationId: "deleteEvent",
        tags: ["Event"],
        parameters: [new OA\Parameter(name: "event", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "Event deleted successfully")]
    )]
    public function destroy($id){
        Event::destroy($id);
        return response()->json(['message' => 'Event deleted successfully.'], 200);
    }
}