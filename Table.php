<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SlickGrid example 1: Basic grid</title>
  <link rel="stylesheet" href="./lib/SlickGrid/slick.grid.css" type="text/css"/>
  <link rel="stylesheet" href="./lib/SlickGrid/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
  
</head>
<body>
<p id="dataDiv" hidden></p>
<table width="100%">
  <tr>
    <td valign="top" width="50%">
      <div id="myGrid" style="width:100%;height:600px;"></div>
    </td>
  </tr>
</table>

<script src="./lib/SlickGrid/lib/jquery-1.7.min.js"></script>
<script src="./lib/SlickGrid/lib/jquery.event.drag-2.2.js"></script>
<script src="./lib/SlickGrid/lib/jquery-ui-1.8.16.custom.min.js"></script>

<script src="./lib/SlickGrid/slick.core.js"></script>
<script src="./lib/SlickGrid/slick.grid.js"></script>

<script>
  // get data in JSON string format
  var table_data;
  var jsonData = $.ajax({
    url: "Data.php",
	contentType: "application/json",
    async: false,
    success: function(data){
      table_data = data;
      table();
    }
  });

  function sorterNumeric(a, b) {
    var x = (isNaN(a[sortcol]) || a[sortcol] === "" || a[sortcol] === null) ? -99e+10 : parseFloat(a[sortcol]);
    var y = (isNaN(b[sortcol]) || b[sortcol] === "" || b[sortcol] === null) ? -99e+10 : parseFloat(b[sortcol]);
    return sortdir * (x === y ? 0 : (x > y ? 1 : -1));
  }

  function sorterStringCompare(a, b) {
    var x = a[sortcol], y = b[sortcol];
    return sortdir * (x === y ? 0 : (x > y ? 1 : -1));
  }
 
  function table() {
     ///Clean up the JSON string from Data.php
  table_data = table_data.replace("In data.php", "");
  table_data = table_data.substring(0, table_data.indexOf(']')+1);
  // create data json array
  var JSONData = JSON.parse(table_data);
  for (var i in JSONData){
    // testing for data rows
    //var name = JSONData[i]['crimeType'];
  }
  var grid;
  var columns = [
    {id: "id", name: "ID", field: "id", width: 25, minWidth: 20, sortable: true, sorter: sorterNumeric},
    {id: "crimeDateTime", name: "Date & Time", field: "crimeDateTime", width: 60, sortable: true, sorter: sorterStringCompare},
    {id: "streetName", name: "Street Name", field: "streetName", sortable: true, sorter: sorterStringCompare},
    {id: "crimeType", name: "Crime Type", field: "crimeType", sortable: true, sorter: sorterStringCompare},
    {id: "weapon", name: "Weapon", field: "weapon", width: 30, sortable: true, sorter: sorterStringCompare},
    {id: "district", name: "District", field: "district", width: 50, sortable: true, sorter: sorterStringCompare},
    {id: "neighborhood", name: "Neighborhood", field: "neighborhood", sortable: true, sorter: sorterStringCompare},
    {id: "latitude", name: "Latitude", field: "latitude", width: 50, sortable: true, sorter: sorterNumeric},
    {id: "longitude", name: "Longitude", field: "longitude", width: 50, sortable: true, sorter: sorterNumeric},
  ];
  var options = {
    enableCellNavigation: true,
    enableColumnReorder: false,
    forceFitColumns: true,
    multiColumnSort: true
  };
    grid = new Slick.Grid("#myGrid", JSONData, columns, options);
    grid.onSort.subscribe(function (e, args) {
      var cols = args.sortCols;

      JSONData.sort(function (dataRow1, dataRow2) {
      for (var i = 0, l = cols.length; i < l; i++) {
          sortdir = cols[i].sortAsc ? 1 : -1;
          sortcol = cols[i].sortCol.field;

          var result = cols[i].sortCol.sorter(dataRow1, dataRow2); // sorter property from column definition comes in play here
          if (result != 0) {
            return result;
          }
        }
        return 0;
      });
      args.grid.invalidateAllRows();
      args.grid.render();
    });
  }
</script>
</body>
</html>