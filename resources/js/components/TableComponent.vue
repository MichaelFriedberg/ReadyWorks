<template>
    <div>
        <h2>Section title</h2>
        <table id="table_id" class="display">
            <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Row 1 Data 1</td>
                <td>Row 1 Data 2</td>
            </tr>
            <tr>
                <td>Row 2 Data 1</td>
                <td>Row 2 Data 2</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import $ from 'jquery';
    import dt from 'datatables.net';

    export default {
        name: "TableComponent",
        mounted() {
            $('#table_id').DataTable(
                 {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "../server_side/scripts/server_processing.php"
                }
            );
        }
    }
</script>

<style scoped>
@import "~datatables.net-dt";
</style>
