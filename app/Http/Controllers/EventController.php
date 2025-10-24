<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller{
    public function index(){
        $query = Event::query();
        $event = $query->latest()->paginate(10);
        return view('event.index', compact('event'))->with('i', (request()->input('page', 1) - 1) * 10);
    }
    public function create(){
        return view('event.create');
    }
    public function store(Request $request){
        Event::create($request-> all());
        return redirect()->route('event.index');
    }
    public function show($id){
        $event=Event::findOrFail($id);
        return view('event.show', compact('event'));
    }
    public function edit($id){
        $event=Event::findOrFail($id);
        return view('event.edit', compact('event'));
    }
    public function update(Request $request, $id){
        $event = Event::findOrFail($id);
        $event->update($request->all());
        return redirect()->route('event.index');
    }
    public function destroy($id){
        Event::destroy($id);
        return redirect()->route('event.index');
    }
}