<?php

namespace App\Http\Controllers;

use App\Models\Tester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TesterController extends Controller{
    public function index(){
        $query = Tester::query();
        $tester = $query->latest()->paginate(10);
        return view('tester.index', compact('tester'))->with('i', (request()->input('page', 1) - 1) * 10);
    }
    public function create(){
        return view('tester.create');
    }
    public function store(Request $request){
        Tester::create($request-> all());
        return redirect()->route('tester.index');
    }
    public function show($id){
        $tester=Tester::findOrFail($id);
        return view('tester.show', compact('tester'));
    }
    public function edit($id){
        $tester=Tester::findOrFail($id);
        return view('tester.edit', compact('tester'));
    }
    public function update(Request $request, $id){
        $tester = Tester::findOrFail($id);
        $tester->update($request->all());
        return redirect()->route('tester.index');
    }
    public function destroy($id){
        Tester::destroy($id);
        return redirect()->route('tester.index');
    }
}