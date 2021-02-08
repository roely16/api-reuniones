<html>
<head>
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
<body>
  <div id="header">
    <h1>Widgets Express</h1>
  </div>
  <div id="footer">
    <p class="page">Page </p>
  </div>
  <div id="content">
    <p>the first page</p>

    <table>
        @foreach($personas as $persona)
        <tr>
            <td>{{ $persona->nombres }}</td>
        </tr>
        @endforeach
    </table>

    <p style="page-break-before: always;">the second page</p>
  </div>
</body>
</html>