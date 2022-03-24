<template>
    <div>
        <h2>Section title</h2>
        <button type="button" @click="clearTable">Clear Filters</button>

        <table id="table_id" class="display">
            <thead>
            <tr>
                <th>Computer Name</th>
                <th>Migration Status</th>
                <th>Description</th>
                <th>Location</th>
                <th class="dropDown">Computer Type</th>
                <th class="searchThis">Computer Model</th>
                <th>Operating System</th>
                <th>Windows 10 Version</th>
                <th>Memory (GB)</th>
                <th>Disk Size (GB)</th>
                <th>Free Space (GB)</th>
                <th>Serial Number</th>
                <th>Business Unit</th>
                <th class="dropDown">Department</th>
                <th>HW Replacement Ordered</th>
                <th>Static IP</th>
                <th>State</th>
                <th>Central Build Site</th>
                <th>Last Logon User</th>
                <th>Asset Vetted</th>
            </tr>
            </thead>
            <tfoot>
            <tr class="hideThis">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="searchAble"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import $ from 'jquery';
    import dt from 'datatables.net';

    export default {
        name: "TableComponent",
        methods: {
            clearTable() {
                    console.log("Clicked reset");
                    // $('#myInputTextField1, #myInputTextField2').val('');
                    $('#table_id').DataTable().search('').draw(); //required after
            }
        },
        mounted() {
            $('#table_id').DataTable(
                 {
                     "processing": true,
                     "serverSide": true,
                     "ajax": "/api/table",
                     "scrollX": true,
                     "scrollY": true,
                     responsive: true,
                     initComplete: function () {
                         this.api().columns('.dropDown').every( function () {
                             var column = this;
                             var select = $('<select class="form-select"><option value=""></option></select>')
                                 .appendTo( $(column.footer()).empty() )
                                 .on( 'change', function () {
                                     var val = $.fn.dataTable.util.escapeRegex(
                                         $(this).val()
                                     );

                                     column
                                         .search( val ? '^'+val+'$' : '', true, false )
                                         .draw();
                                 } );

                             column.data().unique().sort().each( function ( d, j ) {
                                 select.append( '<option value="'+d+'">'+d+'</option>' )
                             } );



                         } );
                         $('th.searchAble').each( function () {
                             var title = $(this).text();
                             $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
                         } );
                         $('.hideThis').first().hide();

                         this.api().columns('.searchThis').every( function () {
                             var that = this;

                             $( 'input', this.footer() ).on( 'keyup change clear', function () {
                                 console.log('Searching');
                                 if ( that.search() !== this.value ) {
                                     that
                                         .search( this.value )
                                         .draw();
                                 }
                             } );
                         } );
                     }
                }
            );
        }
    }

</script>

<style scoped>
@import "~datatables.net-dt";
.searchAble {
    padding-left: 50px;
}
tfoot input {
    width: 50px;
    padding: 3px;
    box-sizing: border-box;
}
</style>
