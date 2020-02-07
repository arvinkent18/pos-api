<?php

namespace App\Http\Controllers\API;

use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = Inventory::all();

        return response()->json([
            'error' => false,
            'inventories' => $inventories,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventory = Inventory::create($request->all());

        return response()->json([
            'error' => false,
            'inventory' => $inventory,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = Inventory::find($id);

        return response()->json([
            'error' => false,
            'inventory' => $inventory,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        $inventory->update($request->all());

        return response()->json([
            'error' => false,
            'inventory' => $inventory,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory = Inventory::find($id);

        $inventory->delete();

        return response()->json([
            'error' => false,
            'message' => 'The inventory with the ID {$inventory->id} has been deleted.',
        ], 200);
    }
}
