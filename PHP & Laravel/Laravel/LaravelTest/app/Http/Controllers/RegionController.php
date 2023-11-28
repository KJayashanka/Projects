<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\Zone;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        return view('regions.index', compact('regions'));
    }

    public function create()
    {
        $zones = Zone::all(); // Retrieve zones for dropdown
        $region_code = $this->generateRegionCode(); // Generate a new region code
        return view('regions.create', compact('zones', 'region_code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'region_name' => 'required',
        ]);

        $region = new Region;
        $region->zone_id = $request->input('zone_id');
        $region->region_code = $this->generateRegionCode(); // Generate the region code
        $region->region_name = $request->input('region_name');
        $region->save();

        return redirect()->route('regions.index')->with('success', 'Region saved successfully!');
    }

    public function edit($id)
    {
        $region = Region::find($id);
        $zones = Zone::all(); // Retrieve zones for dropdown
        return view('regions.edit', compact('region', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'region_name' => 'required',
        ]);

        $region = Region::find($id);
        $region->zone_id = $request->input('zone_id');
        $region->region_name = $request->input('region_name');
        $region->save();

        return redirect()->route('regions.index')->with('success', 'Region updated successfully!');

    }

    public function destroy($id)
    {
        $region = Region::find($id);
        $region->delete();

        return redirect()->route('region.index')->with('success', 'Region deleted successfully!');
    }

    // Function to generate a unique region code
        public function generateRegionCode()
    {
        // Retrieve the last inserted ID from the regions table
        $lastRegion = Region::latest()->first();

        // Generate the region_code based on the last ID
        if ($lastRegion) {
            $lastId = $lastRegion->id;
        } else {
            $lastId = 0;
        }

        $regionCode = 'R' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return $regionCode;
        
    }
}



