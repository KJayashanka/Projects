<?php
namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Territory;

class TerritoryController extends Controller
{
    public function index()
    {
        $territories = Territory::all();
        return view('territories.index', compact('territories'));
    }

    public function create()
{
    $zones = Zone::all();
    $territory_code = $this->generateTerritoryCode(); // Generate a new territory code
    return view('territories.create', compact('zones', 'territory_code'));
}


    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'region_id' => 'required|exists:regions,id', // Validate the region_id
            'territory_name' => 'required',
        ]);

        $territory = new Territory();
        $territory->zone_id = $request->input('zone_id');
        $territory->region_id = $request->input('region_id'); // Assign the region_id
        $territory->territory_code = $this->generateTerritoryCode();
        $territory->territory_name = $request->input('territory_name');
        $territory->save();

        return redirect()->route('territories.index')->with('success', 'Territory saved successfully!');
    }

    public function edit($id)
{
    $territory = Territory::find($id);
    $zones = Zone::all(); // Retrieve zones for the dropdown
    $regions = Region::all(); // Retrieve regions for the dropdown
    return view('territories.edit', compact('territory', 'zones', 'regions'));
}


    public function update(Request $request, Territory $territory, $id)
    {
        // Validate the request data here if needed
        $territory = Territory::find($id);
        $territory->territory_name = $request->input('territory_name');
        $territory->save();

        return redirect()->route('territories.index')->with('success', 'Territory updated successfully!');

    }

    public function destroy($id)
    {
        $territory = Territory::find($id);
        $territory->delete();

        return redirect()->route('territory.index')->with('success', 'Territory deleted successfully!');
    }
    public function generateTerritoryCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
    public function getRegions(Request $request)
    {
        $zoneId = $request->input('zone_id');
        $regions = Region::where('zone_id', $zoneId)->get();
        
        return response()->json($regions);
    }

}

