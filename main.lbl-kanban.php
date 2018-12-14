<html lang="pt_br">
<head>

    <title id='Description'>JavaScript Kanban Header Template.</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/jqx.base.css" type="text/css"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1"/>
    <script type="text/javascript" src="scripts/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxsortable.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxkanban.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="scripts/demos.js"></script>
    <style>
        .jqx-kanban-item-color-status {
            width: 100%;
            height: 25px;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            position: relative;
            margin-top: 0px;
            top: 0px;
        }

        .jqx-kanban-item {
            padding-top: 0px;
            padding-bottom: 0px;
        }

        .jqx-kanban-item-text {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .jqx-kanban-item-avatar {
            top: 9px;
        }

        .jqx-kanban-template-icon {
            position: absolute;
            right: 3px;
            top: 12px;
        }

        body {
            background: #eee;
        }
    </style>
    <?php
    header('Content-type: text/html; charset=utf-8');

    // Pega a string e filtra ela a fim de obter o ticket_id.
    $url = $_SERVER['HTTP_REFERER'];
    $chave_array_url = explode("&", $url);
    $chave_key_url = $chave_array_url['2'];
    $ticket_id = preg_replace("/[^0-9]/", "", $chave_key_url);

    $conn = new mysqli("127.0.0.1", "root", "root", "base");
    $stmt = $conn->prepare("SELECT id, ref, status,description FROM WorkOrder WHERE ticket_id = ?");
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $outp = $result->fetch_all(MYSQLI_ASSOC);
    $outp = array_map('encode_all_strings', $outp);
    function encode_all_strings($arr)
    {
        foreach ($arr as $key => $value) {
            $arr[$key] = utf8_encode($value);
        }
        return $arr;
    }

    ?>
       <script type="text/javascript" charset="UTF-8">
        $(document).ready(function () {

            var fields = [
                     { name: "id", type: "string" },
                     { name: "status", map: "state", type: "string" },
                     { name: "text", map: "label", type: "string" },
                     { name: "tags", type: "string" },
                     { name: "color", map: "hex", type: "string" },
                     { name: "resourceId", type: "number" }
            ];

            var source =
             {
                 localData: [
                          { id: "1161", state: "new", label: "Combine Orders", tags: "orders, combine", hex: "#5dc3f0", resourceId: 3 },
                          { id: "1645", state: "work", label: "Change Billing Address", tags: "billing", hex: "#f19b60", resourceId: 1 },
                          { id: "9213", state: "new", label: "One item added to the cart", tags: "cart", hex: "#5dc3f0", resourceId: 3 },
                          { id: "6546", state: "done", label: "Edit Item Price", tags: "price, edit", hex: "#5dc3f0", resourceId: 4 },
                          { id: "9034", state: "new", label: "Login 404 issue", tags: "issue, login", hex: "#6bbd49" }
                 ],
                 dataType: "array",
                 dataFields: fields
             };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var resourcesAdapterFunc = function () {
                var resourcesSource =
                {
                    localData: [
                          { id: 0, name: "No name", image: "../../../jqwidgets/styles/images/common.png", common: true },
                          { id: 1, name: "Andrew Fuller", image: "../../../images/andrew.png" },
                          { id: 2, name: "Janet Leverling", image: "../../../images/janet.png" },
                          { id: 3, name: "Steven Buchanan", image: "../../../images/steven.png" },
                          { id: 4, name: "Nancy Davolio", image: "../../../images/nancy.png" },
                          { id: 5, name: "Michael Buchanan", image: "../../../images/Michael.png" },
                          { id: 6, name: "Margaret Buchanan", image: "../../../images/margaret.png" },
                          { id: 7, name: "Robert Buchanan", image: "../../../images/robert.png" },
                          { id: 8, name: "Laura Buchanan", image: "../../../images/Laura.png" },
                          { id: 9, name: "Laura Buchanan", image: "../../../images/Anne.png" }
                    ],
                    dataType: "array",
                    dataFields: [
                         { name: "id", type: "number" },
                         { name: "name", type: "string" },
                         { name: "image", type: "string" },
                         { name: "common", type: "boolean" }
                    ]
                };

                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            }

            var getIconClassName = function () {
                switch (theme) {
                    case "darkblue":
                    case "black":
                    case "shinyblack":
                    case "ui-le-frog":
                    case "metrodark":
                    case "orange":
                    case "darkblue":
                    case "highcontrast":
                    case "ui-sunny":
                    case "ui-darkness":
                        return "jqx-icon-plus-alt-white ";
                }
                return "jqx-icon-plus-alt";
            }

            $('#kanban').jqxKanban({
                template: "<div class='jqx-kanban-item' id=''>"
                        + "<div class='jqx-kanban-item-color-status'></div>"
                        + "<div style='display: none;' class='jqx-kanban-item-avatar'></div>"
                        + "<div class='jqx-icon jqx-icon-close-white jqx-kanban-item-template-content jqx-kanban-template-icon'></div>"
                        + "<div class='jqx-kanban-item-text'></div>"
                        + "<div style='display: none;' class='jqx-kanban-item-footer'></div>"
                + "</div>",
                width: getWidth('kanban'),
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                itemRenderer: function(element, item, resource)
                {
                    $(element).find(".jqx-kanban-item-color-status").html("<span style='line-height: 23px; margin-left: 5px; color:white;'>" + resource.name + "</span>");
                },
                columns: [
                    { text: "Backlog", iconClassName: getIconClassName(), dataField: "new" },
                    { text: "In Progress", iconClassName: getIconClassName(), dataField: "work" },
                    { text: "Done", iconClassName: getIconClassName(), dataField: "done" }
                ]
            });

        });
    </script>

</head>
<body>
<div id="kanban"></div>
</body>
</html>
