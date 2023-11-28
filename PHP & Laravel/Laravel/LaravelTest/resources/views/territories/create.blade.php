@extends('layouts.app')

@section('content')
    <h1>Territory Registration</h1>
    <form method="post" action="{{ route('territory.store') }}">
        @csrf
        <div class="form-group">
            <label for="zone_id">Select Zone:</label>
            <select name="zone_id" id="zone_id" class="form-control">
                <option value="">Select Zone</option>
                @foreach ($zones as $zone)
                    <option value="{{ $zone['id'] }}">{{ $zone['short_description'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="region_id">Select Region:</label>
            <select name="region_id" id="region_id" class="form-control">
                <option value="">Select Region</option>
            </select>
        </div>
        <script>
        document.getElementById("zone_id").addEventListener("change", function () {
            var zoneId = this.value;
            var regionSelect = document.getElementById("region_id");

            // Clear existing options
            regionSelect.innerHTML = '<option value="">Select Region</option>';

            // If a zone is selected, make an AJAX request to get relevant regions
            if (zoneId !== "") {
                // You can use Axios or other libraries for better AJAX handling
                fetch(`/get-regions?zone_id=${zoneId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(region => {
                            var option = document.createElement("option");
                            option.value = region.id;
                            option.text = region.region_name; // Use the correct property
                            regionSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>

        <div class="form-group">
            <label for="territory_code">Territory Code:</label>
            <input type="text" name="territory_code" value="{{ $territory_code }}" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="territory_name">Territory Name:</label>
            <input type="text" name="territory_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Territory</button>
    </form>
@endsection
