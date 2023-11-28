// public/js/purchase_order.js
function populateHiddenFields(checkbox) {
    var row = checkbox.parentNode.parentNode;
    var hiddenInputs = row.getElementsByTagName('input');

    for (var i = 0; i < hiddenInputs.length; i++) {
        if (hiddenInputs[i].type === 'hidden') {
            var fieldName = hiddenInputs[i].name;
            var dataIndex = fieldName.lastIndexOf('['); // Get the index of the last '[' character
            var index = fieldName.substring(dataIndex + 1, fieldName.length - 1); // Extract the index from the field name

            // Use the index to fetch the corresponding data from the row
            var cellIndex = i - 1; // The cell containing the data
            var cellValue = row.cells[cellIndex].textContent.trim();

            // Set the value of the hidden field based on the field name and index
            hiddenInputs[i].value = cellValue;
        }
    }
}

function calculateTotalPrice(input) {
    var row = input.parentNode.parentNode;
    var mrp = parseFloat(row.cells[4].textContent);
    var units = parseFloat(input.value);
    var totalPriceCell = row.cells[7];
    totalPriceCell.textContent = (mrp * units).toFixed(2);
}

// Attach onchange event listener to all unit amount inputs
var unitAmountInputs = document.getElementsByName('unit_amount[]');
for (var i = 0; i < unitAmountInputs.length; i++) {
    unitAmountInputs[i].addEventListener('change', function() {
        calculateTotalPrice(this);
    });
}


document.getElementById("zone_id").addEventListener("change", function() {
    var zoneId = this.value;
    var regionSelect = document.getElementById("region_id");

    // Clear existing options
    regionSelect.innerHTML = '<option value="">Select Region</option>';

    // If a zone is selected, make an AJAX request to get relevant regions
    if (zoneId !== "") {
        fetch('/get-regions/' + zoneId)
            .then(response => response.json())
            .then(data => {
                data.forEach(region => {
                    var option = document.createElement("option");
                    option.value = region.id;
                    option.text = region.name;
                    regionSelect.appendChild(option);
                });
            });
    }
});

document.getElementById("region_id").addEventListener("change", function() {
    var regionId = this.value;
    var territorySelect = document.getElementById("territory_id");

    // Clear existing options
    territorySelect.innerHTML = '<option value="">Select Territory</option>';

    // If a region is selected, make an AJAX request to get relevant territories
    if (regionId !== "") {
        fetch('/get-territories/' + regionId)
            .then(response => response.json())
            .then(data => {
                data.forEach(territory => {
                    var option = document.createElement("option");
                    option.value = territory.id;
                    option.text = territory.name;
                    territorySelect.appendChild(option);
                });
            });
    }
});
