<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class VirtualApiController
{
    #[OA\Get(
        path: "/api/virtual/{table}",
        summary: "List records of a given table",
        tags: ["Virtual API"],
        parameters: [new OA\Parameter(name: "table", in: "path", required: true)],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function index(Request $request, $table)
    {
        abort_unless(Schema::hasTable($table), 404, "Table not found");
        $data = DB::table($table)->paginate(10);
        return response()->json($data);
    }

    #[OA\Post(
        path: "/api/virtual/{table}",
        summary: "Insert a new record into a given table",
        tags: ["Virtual API"],
        parameters: [new OA\Parameter(name: "table", in: "path", required: true)],
        responses: [new OA\Response(response: 201, description: "Created")]
    )]
    public function store(Request $request, $table)
    {
        abort_unless(Schema::hasTable($table), 404, "Table not found");
        $input = $request->except(['_token']);
        $id = DB::table($table)->insertGetId($input);
        return response()->json(['id' => $id, 'message' => 'Record created'], 201);
    }

    #[OA\Get(
        path: "/api/virtual/{table}/{id}",
        summary: "Show a record by ID",
        tags: ["Virtual API"],
        parameters: [
            new OA\Parameter(name: "table", in: "path", required: true),
            new OA\Parameter(name: "id", in: "path", required: true)
        ],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function show($table, $id)
    {
        abort_unless(Schema::hasTable($table), 404, "Table not found");
        $record = DB::table($table)->find($id);
        return response()->json($record ?? ['error' => 'Not found'], $record ? 200 : 404);
    }

    #[OA\Put(
        path: "/api/virtual/{table}/{id}",
        summary: "Update record by ID",
        tags: ["Virtual API"],
        parameters: [
            new OA\Parameter(name: "table", in: "path", required: true),
            new OA\Parameter(name: "id", in: "path", required: true)
        ],
        responses: [new OA\Response(response: 200, description: "Updated")]
    )]
    public function update(Request $request, $table, $id)
    {
        abort_unless(Schema::hasTable($table), 404, "Table not found");
        $input = $request->except(['_token']);
        DB::table($table)->where('id', $id)->update($input);
        return response()->json(['message' => 'Record updated']);
    }

    #[OA\Delete(
        path: "/api/virtual/{table}/{id}",
        summary: "Delete record by ID",
        tags: ["Virtual API"],
        parameters: [
            new OA\Parameter(name: "table", in: "path", required: true),
            new OA\Parameter(name: "id", in: "path", required: true)
        ],
        responses: [new OA\Response(response: 200, description: "Deleted")]
    )]
    public function destroy($table, $id)
    {
        abort_unless(Schema::hasTable($table), 404, "Table not found");
        DB::table($table)->where('id', $id)->delete();
        return response()->json(['message' => 'Record deleted']);
    }
}
