jQuery(document).ready(function ($) {
    let compareProducts = [];

    // Function to open the comparison popup
    function openComparePopup() {
        let popup = $("#wpc-compare-popup");
        let list = popup.find(".wpc-compare-list");
        list.empty();

        if (compareProducts.length === 0) {
            list.append("<p>No products added for comparison.</p>");
        } else {
            compareProducts.forEach(product => {
                let attributes = product.attributes
                    ? Object.entries(product.attributes).map(([key, value]) => `<strong>${key}:</strong> ${value}`).join("<br>")
                    : "No attributes available.";

                list.append(`
                    <div class="wpc-product">
                        <img src="${product.image}" alt="${product.title}" />
                        <h4>${product.title}</h4>
                        <p>${product.description}</p>
                        <p><strong>Price:</strong> ${product.price}</p>
                        <p>${attributes}</p>
                        <button class="wpc-remove-product" data-id="${product.id}">Remove</button>
                    </div>
                `);
            });
        }

        popup.show();
    }

    $(".wpc-compare-btn").click(function () {
        let productId = $(this).data("product-id");

        if (compareProducts.some(p => p.id === productId)) {
            alert("This product is already in the comparison list.");
            return;
        }

        $.ajax({
            type: "POST",
            url: wpc_ajax.ajax_url,
            data: {
                action: "wpc_add_to_compare",
                product_id: productId
            },
            success: function (response) {
                if (response.success) {
                    compareProducts.push(response.data);
                    openComparePopup();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $(".wpc-close-popup").click(function () {
        $("#wpc-compare-popup").hide();
    });

    $(document).on("click", ".wpc-remove-product", function () {
        let productId = $(this).data("id");
        compareProducts = compareProducts.filter(p => p.id !== productId);
        openComparePopup();
    });
});
