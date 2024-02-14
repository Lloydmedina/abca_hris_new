function searchKit() {
    var searchedText = $("#txtSearchBox").val();
    table = $("#KitTable").DataTable();
    table.destroy();
    table = $("#KitTable").DataTable({
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "paging": true,
        "pageLength": 5,
        "pagingType": "full_numbers",
        "processing": true, //TO SHOW PROGRESS BAR
        "serverSide": true,//TO ACTIVATE SERVER SIDE
        "filter": false, //TO DISABLE FILTER
        "orderMulti": false, //TO DISABLE MULTI COLUMN ORDER
        "ajax": {
            "type": "POST",
            "datatype": "json",
            "url": "/Admin/SearchKit",
            "data": {
                "searchText": searchedText
            }
        },//DEFINING COLUMNS <TD>
        "columnDefs": [{
            "targets": 1,
            "searchable": false,
            "orderable": false,
            "classname": "dt-body-center",
            "render": function (data, type, row, meta) {
                return '<img src="' + row["image_path"] + '" style="width: 100px" alt="...">';
            }
        }, {
            "targets": 6,
            "searchable": false,
            "orderable": false,
            "classname": "dt-body-center",
            "render": function (data, type, row) {
                return '<div class="form-inline">\
                            <div class="form-group">\
                            <form action="/Admin/EditKit" method="post">\
                                <input hidden id="guid" name="guid" value="' + row["id"] + '" />\
                                <button type="submit"  class="btn btn-link btn-warning btn-just-icon edit">\
                                        <i class="material-icons">\
                                            edit\
                                        </i>\
                                </button>\
                            </form>\
                            </div>\
                            <div class="form-group">\
                                <form action="/Admin/DeleteKit" method="post">\
                                    <input hidden id="guid" name="guid" value="' + row["id"] + '" />\
                                    <button type="submit" class="btn btn-link btn-danger btn-just-icon remove">\
                                            <i class="material-icons">\
                                                close\
                                            </i>\
                                        </button>\
                                </form>\
                            </div>\
                        </div>';
            }
        }],
        "columns": [
            { "data": "code", "name": "code" },
             { "data": "code", "name": "code" },
            { "data": "kit", "name": "kit" },
            { "data": "remarks", "name": "remarks" },
            { "data": "item_list", "name": "item_list" },
            { "data": "price", "name": "price" },
            { "autoWidth": true }
        ]
    });
}