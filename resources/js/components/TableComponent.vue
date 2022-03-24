<template>
    <div>
        <h2>Section title</h2>
        <table id="table_id" class="display">
            <thead>
            <tr>
                <th>Computer Name</th>
                <th>Migration Status</th>
                <th>Description</th>
                <th>Location</th>
                <th>Computer Type</th>
                <th>Computer Model</th>
                <th>Operating System</th>
                <th>Windows 10 Version</th>
                <th>Memory (GB)</th>
                <th>Disk Size (GB)</th>
                <th>Free Space (GB)</th>
                <th>Serial Number</th>
                <th>Business Unit</th>
                <th>Department</th>
                <th>HW Replacement Ordered</th>
                <th>Static IP</th>
                <th>State</th>
                <th>Central Build Site</th>
                <th>Last Logon User</th>
                <th>Asset Vetted</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Computer Name</th>
                <th>Migration Status</th>
                <th>Description</th>
                <th>Location</th>
                <th>Computer Type</th>
                <th>Computer Model</th>
                <th>Operating System</th>
                <th>Windows 10 Version</th>
                <th>Memory (GB)</th>
                <th>Disk Size (GB)</th>
                <th>Free Space (GB)</th>
                <th>Serial Number</th>
                <th>Business Unit</th>
                <th>Department</th>
                <th>HW Replacement Ordered</th>
                <th>Static IP</th>
                <th>State</th>
                <th>Central Build Site</th>
                <th>Last Logon User</th>
                <th>Asset Vetted</th>
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
        mounted() {
            $('#table_id').DataTable(
                 {
                    "processing": true,
                    "serverSide": true,
                    "ajax": "/api/table"
                }
            );
        }
    }
</script>

<style scoped>
@import "~datatables.net-dt";
</style>
