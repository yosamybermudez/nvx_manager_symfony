$(document).ready(function() {
    // Tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Select Picker
    // $('.select-picker').selectpicker();
    $('.form-picker').select2({
        tags: true,
        allowClear: true,
        placeholder: "Type or select an option"
    });
    $('.form-picker').parent().find('.select2-selection').addClass('select2-bootstrap');


    //Datatable
    $.fn.dataTable.ext.type.order['date-dd-mmm-yyyy-pre'] = function(date) {
        var dateParts = date.split(' ');
        var day = parseInt(dateParts[0], 10);
        var month = new Date(Date.parse(dateParts[1] +" 1, 2024")).getMonth();
        var year = parseInt(dateParts[2], 10);
        return new Date(year, month, day).getTime();
    };

    $('.data-table').DataTable({
        "order": [[0, "asc"]],
        "columnDefs": [
            { "targets": -1, "orderable": false },
        ]
    });
    $('.data-table-invoice-quote').DataTable({
        "order": [[0, "desc"], [1, "desc"]],
        "columnDefs": [
            { "targets": -1, "orderable": false },
            { "targets": 0, "type": "date-dd-mmm-yyyy" },
        ]
    });
    $('.data-table-transactions').DataTable({
        "order": [[0, "desc"]],
        "columnDefs": [
            { "targets": -1, "orderable": false },
            { "targets": 0, "type": "date-dd-mmm-yyyy" },
        ]
    });

    $('.data-table-item').DataTable({
        "order": [[0, "asc"], [1, "asc"]],
        "columnDefs": [
            { "targets": -1, "orderable": false },
        ]
    });

    // Delete Modal
    $('.delete-item').on('click', function () {
        console.log($(this).data('href'));
        $('form#delete-form').attr('action', $(this).data('action'));
        $('form#delete-form input[name="_token"]').val($(this).data('token'));
        $('form#delete-form input#confirm-delete').focus();
    });

    //Add Table
    $('.add-item').click(function() {
        console.log('Item Added');
        var tableBody = $('#add-table tbody');
        var prototype = tableBody.data('prototype');
        var index = $('tbody tr').length;
        var newForm = prototype.replace(/__name__/g, index);

        var $newRow = $('<tr></tr>').html(newForm);
        $newRow.find('.remove-item').click(function() {
            $(this).closest('tr').remove();
        });
        tableBody.append($newRow);

        $newRow.find('.form-picker').select2({
            tags: true,
            allowClear: true,
            placeholder: "Type or select an option"
        });
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('change keyup', '#add-table .ii-variable', function() {
        var element = $(this);
        var iiItem= element.closest('tr').find('.ii-item').first();
        var iiQuantity = element.closest('tr').find('.ii-quantity').first();
        var iiRate = element.closest('tr').find('.ii-rate').first();
        var iiAmount = element.closest('tr').find('.ii-amount').first();
        var iiAmountDisabled = element.closest('tr').find('.ii-amount-disabled').first();
        var quantity = iiQuantity.val();
        var selectedPrice = iiRate.val();
                if(element.hasClass('ii-item')){
            selectedPrice = iiItem.find('option:selected').data('price');
        }
        iiRate.val(selectedPrice);
        var amount = selectedPrice * quantity;
        if(iiItem.val() === ''){
            iiRate.empty();
            iiAmount.empty();
            iiAmountDisabled.empty();
        }
        iiAmount.val(amount);
        iiAmountDisabled.val(amount.toFixed(2));


        var totalAmount = 0;
        $('#add-table').find('.ii-amount').each(function () {
            var value = parseFloat($(this).val()) || 0; // Convertir a número, o 0 si está vacío o no es válido
            totalAmount += value;
        });

        var iiTotalAmmount = $('#add-table').find('.ii-total-amount').first();
        iiTotalAmmount.text(totalAmount.toFixed(2));
        
    });

    // Page New Contact
    $('.displayname-trigger').on('change keyup', addOptions);
    $('.displayname-form-picker').on('click', addOptions);

    function addOptions() {
        var elementForm = $(this).closest('form');
        var displayName = elementForm.find('.contact-displayname').first();

        var salutation = elementForm.find('.contact-salutation').first().val();
        var firstName = elementForm.find('.contact-firstname').first().val();
        var lastName = elementForm.find('.contact-lastname').first().val();
        var company = elementForm.find('.contact-company').first().val();

        var array = [];

        if (company) {
            array.push(company);
        }

        if (salutation && firstName && lastName) {
            array.push([salutation, firstName, lastName].join(' '));
        }
        if (firstName && lastName) {
            array.push([firstName, lastName].join(' '));
            array.push([lastName, firstName].join(', '));
        }

        displayName.empty();
        array.forEach(function(value) {
            var newOption = new Option(value, value, false, false);
            displayName.append(newOption);
        }.bind(this));

    }
});