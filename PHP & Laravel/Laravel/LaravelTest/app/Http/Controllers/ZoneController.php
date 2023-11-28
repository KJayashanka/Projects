<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::all();
        return view('zone.index', compact('zones'));
    }

    public function create()
{
    $generatedZoneCode = $this->generateZoneCode();
    return view('zone.create', compact('generatedZoneCode'));
}

    public function store(Request $request)
    
    {
        // Validate the request data here if needed
        $zone = new Zone();
        $zone->long_description = $request->input('long_description');
        $zone->short_description = $request->input('short_description');
        $zone->zone_code = $this->generateZoneCode(); // Generate zone_code
        $zone->save();

        $request->validate([
            'long_description' => 'required',
            'short_description' => 'required',
        ]);

        return redirect()->route('zone.index')->with('success', 'Zone saved successfully!');
        
    }

    public function edit($id)
    {
        $zone = Zone::find($id);
        return view('zone.edit', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data here if needed
        $zone = Zone::find($id);
        $zone->long_description = $request->input('long_description');
        $zone->short_description = $request->input('short_description');
        $zone->save();

        return redirect()->route('zone.index')->with('success', 'Zone updated successfully!');
    }

    private function generateZoneCode()
    {
        // Retrieve the last inserted ID from the zones table
        $lastZone = Zone::latest()->first();

        // Generate the zone_code based on the last ID
        if ($lastZone) {
            $lastId = $lastZone->id;
        } else {
            $lastId = 0;
        }

        $zoneCode = 'Z' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return $zoneCode;
    }
}
