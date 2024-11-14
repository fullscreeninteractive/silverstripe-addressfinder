document.addEventListener("DOMContentLoaded", function () {
    /**
     * Sets up the Address Finder field.
     * @param {HTMLElement} elem
     */
    var setupAddressFinderField = function (elem) {
        var widget,
            key = elem.getAttribute("data-api-key"),
            address = elem.querySelector(".address_finder_address"),
            input = elem.querySelector("input"),
            manual = elem.querySelector(".manual_address"),
            toggle = elem.querySelector(".toggle_manual_address");

        var useManual = null;
        var field = null;

        if (manual) {
            useManual = manual.querySelector("input[name*=ManualAddress]");
        }

        if (address) {
            field = address.querySelector("input");
        }

        if (!field) {
            console.error(
                "AddressFinder: Could not find address field in element",
                elem
            );

            return;
        }

        // Update UI
        if (toggle) {
            toggle.style.display = "block";
        }

        address.style.display = "block";

        if (useManual && useManual.value !== "1") {
            manual.style.display = "none";
        }

        // Create widget
        widget = new AddressFinder.Widget(field, key, "NZ", {
            container: elem.querySelector(".addressfinder__holder"),
        });

        // Update manual fields and hidden metadata
        widget.on("result:select", function (value, item) {
            for (var i = 1; i <= 6; i++) {
                var postalInput = manual.querySelector(
                    "input[name*=PostalLine" + i + "]"
                );
                if (postalInput) {
                    postalInput.value = item["postal_line_" + i] || "";
                }
            }

            if (manual) {
                manual.querySelector("input[name*=Suburb]").value =
                    item.suburb || "";
                manual.querySelector("input[name*=Region]").value =
                    item.region || "";
                manual.querySelector("input[name*=City]").value =
                    item.city || "";
                manual.querySelector("input[name*=Postcode]").value =
                    item.postcode || "";
                manual.querySelector("input[name*=Longitude]").value =
                    item.x || "";
                manual.querySelector("input[name*=Latitude]").value =
                    item.y || "";
            }

            var event = new Event("addressselected", { bubbles: true });
            document.body.dispatchEvent(event);
        });

        // Click handler to toggle manual div
        toggle?.addEventListener("click", function (e) {
            e.preventDefault();

            if (
                manual.style.display === "none" ||
                manual.style.display === ""
            ) {
                manual.style.display = "block";
                useManual.value = "1";
            } else {
                manual.style.display = "none";
                useManual.value = "0";
            }

            return false;
        });

        // Focus event to hide manual
        input?.addEventListener("focus", function () {
            manual.style.display = "none";
        });
    };

    var addressFinderElements = document.querySelectorAll(".address_finder");
    addressFinderElements.forEach(function (elem) {
        setupAddressFinderField(elem);
    });

    window.setupAddressFinderField = setupAddressFinderField;
});
