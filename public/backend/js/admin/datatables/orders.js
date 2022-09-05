(function($) {
    "use strict";
    $(document).ready(function () {
        $('#AdvertiseTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: $('#table-url').data("url"),
            columns: [
                {
                    data: 'Order_Number',
                    name: 'Order_Number'
                },
                {
                    data: 'User',
                    name: 'User'
                },
                {
                    data: 'Products',
                    name: 'Products'
                },
                {
                    data: 'types',
                    name: 'types'
                },
                {
                    data: 'GrandTotal',
                    name: 'GrandTotal'
                },
                {
                    data: 'Coupon',
                    name: 'Coupon'
                },
                {
                    data: 'Payment_Method',
                    name: 'Payment_Method'
                },
                {
                    data: 'digital_goods',
                    name: 'digital_goods'
                },
                {
                    data: 'Status',
                    name: 'Status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ]
        });
    });

    $('#print-now').on('click', function() {
        let w=window.open();
        w.document.write(document.getElementById('printDiv').innerHTML);
        w.print();
        w.close();
    });
})(jQuery)
