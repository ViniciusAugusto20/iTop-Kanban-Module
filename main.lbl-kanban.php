<html lang="pt_br">
<head>

    <title id='Description'>JavaScript Kanban Header Template.</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/jqx.base.css" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />
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
            position:relative;
            margin-top:0px;
            top: 0px;
        }
        .jqx-kanban-item {
            padding-top: 0px;
            padding-bottom:0px;
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
            top:12px;
        }
    </style>
    <?php
    header ('Content-type: text/html; charset=utf-8');

    // Pega a string e filtra ela a fim de obter o ticket_id.
    $url = $_SERVER['HTTP_REFERER'];
    $chave_array_url = explode("&", $url);
    $chave_key_url =  $chave_array_url['2'];
    $ticket_id =  preg_replace("/[^0-9]/", "", $chave_key_url);

    $conn = new mysqli("127.0.0.1", "root", "root", "itop");
    $stmt = $conn->prepare("SELECT id, ref, status,description FROM WorkOrder WHERE ticket_id = ?");
    $stmt->bind_param("s", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $outp = $result->fetch_all( MYSQLI_ASSOC);
    $outp = array_map('encode_all_strings',$outp);
    function encode_all_strings($arr) {
        foreach($arr as $key => $value) {
            $arr[$key] = utf8_encode($value);
        }
        return $arr;
    }

    ?>
    <script type="text/javascript" charset="UTF-8">
        $(document).ready(function () {
            var fields = [
                { name: "id", map: "id", type: "string" },
                { name: "name", map:"ref", type: "string" },
                { name: "status", map: "status", type: "string" },
                { name: "text", map: "description", type: "string" }
            ];
            var data = JSON.parse( '<?php echo json_encode($outp) ?>' );

            var source =

                {
                    localData:data,
                    dataType: "array",
                    dataFields: fields

                };
            var dataAdapter = new $.jqx.dataAdapter(source);

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

                }
                return "jqx-icon-plus-alt";
            }
            $('#kanban').jqxKanban({
                width: getWidth('kanban'),
                template: "<div class='jqx-kanban-item' id=''>"
                + "<div class='jqx-kanban-item-color-status'></div>"
                + "<div style='display: none;' class='jqx-kanban-item-avatar'></div>"
                + "<div class='jqx-icon jqx-icon-close jqx-kanban-item-template-content jqx-kanban-template-icon'></div>"
                + "<div class='jqx-kanban-item-text'></div>"
                + "<div style='display: none;' class='jqx-kanban-item-footer'></div>"
                + "</div>",
                source:  dataAdapter,
                // render items.
                itemRenderer: function(element, item, resource)
                {
                    $(element).find(".jqx-kanban-item-color-status").html("<span style='line-height: 23px; margin-left: 5px;'>" + resource.name +  "</span>");
                    $(element).find(".jqx-kanban-item-text").css('background', item.color);
                },
                columns: [
                    { text: "Aguardando atendimento", iconClassName: getIconClassName(), dataField: "aguardando", maxItems: 40 },
                    {text: "Em atendimeto", iconClassName: getIconClassName(), dataField: "atendido", maxItems: 40 },
                    { text: "Resolvido", iconClassName: getIconClassName(), dataField: "closed", maxItems: 80 }
                ],
                // render column headers.
                columnRenderer: function (element, collapsedElement, column) {
                    var columnItems = $("#kanban").jqxKanban('getColumnItems', column.dataField).length;
                    // update header's status.
                    element.find(".jqx-kanban-column-header-status").html(" (" + columnItems + "/" + column.maxItems + ")");
                    // update collapsed header's status.
                    collapsedElement.find(".jqx-kanban-column-header-status").html(" (" + columnItems + "/" + column.maxItems + ")");

                }

            });
            var estado;
            $('#kanban').on('itemMoved', function (event) {
                var args = event.args;
                estado = args.newColumn.text;
                id = args.itemId;
                update();
            });

            function update() {

                var url = "kbscript.php";
                var params = "estado=" + estado + "&id=" + id;
                var http = new XMLHttpRequest();
                http.open("POST", url, true);

                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                http.setRequestHeader("Content-length", params.length);
                http.setRequestHeader("Connection", "close");

                http.onreadystatechange = function() {
                    if(http.readyState == 4 && http.status == 200) {
                        alert("Estado Atualizado para "+estado);
                    }
                }
                http.send(params);

            }

        });
    </script>

</head>
<body>
<div id="kanban"></div>
</body>
</html>
