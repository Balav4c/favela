<script>
var categoryTable;
$(document).ready(function() {
    $('.fs').hide();

    categoryTable = $('#categoryTable').DataTable({
        'processing': true,
        'serverSide': false,
        'ajax': baseUrl + "category/loadCategories",
        'columns': [
            {
                data: null, // Use null for serial number
                render: function(data, type, row, meta) {
                    return meta.row + 1; // Serial number (1-based index)
                }
            },
            {
                data: 'category_name',
                render: function(data, type, row) {
                    return data +
                        ` <a href="javascript:void(0);" class="toggle-subcategories" data-shead="${row.cat_id}">
                            <i class="fa fa-plus plus-icon"></i>
                        </a>
                        <div id="shead-ldg-${row.cat_id}" class="subcategory-list" style="display:none;">` +
                        (row.subcategories.length > 0 ?
                            row.subcategories.map((sub, index) =>
                                `<div class='subcategory-item'>
                                    <span class='sub-name'>${sub}</span>
                                    <button class='delete-subcategory' data-category-id='${row.cat_id}' data-subindex='${index}'>
                                        <i class='fa fa-trash-o'></i>
                                    </button>
                                </div>`).join('') :
                            `<div class='subcategory-item'>No subcategories found</div>`) +
                        `</div>`;
                }
            },
            {
                data: 'diminish_rate',
                render: function(data) {
                    return data + '%';
                }
            },
            {
                data: 'cat_id',
                render: function(data) {
                    return `<div style="display:flex;">
                        <a href="${baseUrl}category/${data}"><i class="fa fa-pencil-square-o"></i></a>
                        <button class="delete-category" data-id="${data}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>`;

                    // <button class="update-category" data-id="${data}">
                    //         <i class="fa fa-pencil-square-o"></i>
                    //     </button>
                }
            }
        ]
    });

    // Handle dynamically loaded elements using event delegation
    $(document).on('click', '.toggle-subcategories', function() {
        var catId = $(this).data('shead');
        var subcategoryDiv = $('#shead-ldg-' + catId);

        if (subcategoryDiv.is(':visible')) {
            subcategoryDiv.slideUp();
            $(this).find('.plus-icon').removeClass('fa-minus').addClass('fa-plus');
        } else {
            subcategoryDiv.slideDown();
            $(this).find('.plus-icon').removeClass('fa-plus').addClass('fa-minus');
        }
    });

    // Category Add
    $('#category-btn').click(function() {
        var url = baseUrl + "category/save";

        $.post(url, $('#createCategory').serialize(), function(data) {
            $('#createCategory')[0].reset();

            if (data.status === 'success') {
                initAlert(data.message, 1);
                categoryTable.ajax.reload();
            } else {
                initAlert(data.message, 0);
                $('#createCategory')[0].reset();
            }
        }, 'json');
    });

    // Delete category
    $(document).on("click", ".delete-category", function() {
        var categoryId = $(this).data("id");
        var url = baseUrl + "category/delete";
        // if (confirm("Are you sure you want to delete this subcategory?")) {
        //     $.post(url, {  category_id: categoryId, }, function(data) {
        //         if (data.status === "success") {
        //             initAlert(data.message, 1);
        //             categoryTable.ajax.reload();
        //         } else {
        //             initAlert(data.message, 0);
        //         }
        //     }, "json");
        // }


        initDelConfirmWithBody(
            "Are you sure you want to delete this category? This action cannot be undone.",
            2, url, {
                category_id: categoryId
            }, categoryTable);
    });
  //Subcategory delete
    $(document).on("click", ".delete-subcategory", function() {
        var categoryId = $(this).data("category-id");
        var subIndex = $(this).data("subindex");
        var url = baseUrl + "subcategory/delete";
        initDelConfirmWithBody(
            "Are you sure you want to delete this category? This action cannot be undone.",
            2, url, {
                category_id: categoryId,
                sub_index: subIndex
            }, categoryTable);


    });
    //Dimish rate prepopulation
    $(document).ready(function() {
        $('#cateName').on('input', function() {
            var categoryName = $(this).val();
            if (categoryName.length > 2) { // Avoid unnecessary AJAX calls
                $.ajax({
                    url: "<?= base_url('category/getDiminishRate') ?>", 
                    type: "POST",
                    data: {
                        category_name: categoryName
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#dimiRate').val(response.diminish_rate).prop(
                                'readonly', true); 
                        } else {
                            $('#dimiRate').val('').prop('readonly',
                            false); 
                        }
                    }
                });
            } else {
                $('#dimiRate').prop('readonly', false); 
            }
        });
    });

});
</script>