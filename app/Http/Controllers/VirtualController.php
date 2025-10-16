<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\VirCreator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VirtualController extends Controller
{
    public function index($table)
    {
        $model = VirCreator::virtualModel($table);
        $fields = array_keys($model['fields']);
        $rows = VirCreator::handle($table, 'index');

        $tableHeaders = '';
        foreach ($fields as $f){
            $tableHeaders .= "<th class='border px-4 py-2 text-center text-sm font-medium text-gray-700'>".ucfirst($f)."</th>";
        }

        return view('virtual::index', [
            'table' => $table,
            'rows' => $rows,
            'fields' => $fields,
            'tableHeaders' => $tableHeaders,
            'modelName' => ucfirst($table),
            'modelNameLower' => strtolower($table),
        ]);
    }

    public function create($table)
{
    $columns = VirCreator::getSchema($table);
    $modelName = Str::singular(ucfirst($table));
    $modelNameLower = strtolower($modelName);

    $creator = new \App\Helpers\VirCreator();
    $formFields = $creator->generateFormFields($columns, 'create', $modelNameLower);

    return view('virtual::create', compact('modelName', 'modelNameLower', 'formFields'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $table)
    {
        $data = $request->except(['_token', '_method']);
        $data['created_at'] = now();
        $data['updated_at'] = now();
        VirCreator::handle($table, 'store', null, $data);
        return redirect()
            ->route('virtual.index', ['table' => $table])
            ->with('success', ucfirst($table) . ' created successfully.');
    }

    public function show($table,$id){
        $columns  = VirCreator::getSchema($table);
        $modelName = ucfirst($table);
        $modelNameLower = strtolower($modelName);
        $row=VirCreator::handle($table, 'show', $id);
        $creator = new \App\Helpers\VirCreator();
        $showFields = $creator->generateShowFields($columns, $modelNameLower, $row);

        return view('virtual::show',[
            'modelName' => $modelName,
            'modelNameLower' => $modelNameLower,
            $modelNameLower=>$row,
            'showFields' => $showFields,
        ]);
    }
    public function edit($table, $id)
{
    $columns = VirCreator::getSchema($table);
    $modelName = ucfirst($table);
    $modelNameLower = strtolower($modelName);
    $row = VirCreator::handle($table, 'show', $id);

    $creator = new \App\Helpers\VirCreator();
    $formFields = $creator->generateFormFields($columns, 'edit', $modelNameLower, $row);

    return view('virtual::edit', [
        'modelName' => $modelName,
        'modelNameLower' => $modelNameLower,
        'id' => $id,
        $modelNameLower => $row,
        'formFields' => $formFields,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $table, $id)
    {
        $data = $request->except(['_token','_method']);
        $data['updated_at'] = now();
        VirCreator::handle($table, 'update',$id,$data);

        return redirect()
            ->route('virtual.index', ['table' => $table])
            ->with('success', ucfirst($table) . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($table, $id)
    {
        VirCreator::handle($table, 'destroy', $id);

        return redirect()
            ->route('virtual.index', ['table' => $table])
            ->with('success', ucfirst($table) . ' deleted successfully.');
    }
}
