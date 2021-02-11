<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
  -->
  

<body>
  <!--
  <div id="header">
    <h1>Widgets Express</h1>
  </div>
  <div id="footer">
    <p class="page">Page </p>
  </div>
  -->
  <div id="content">
    <div class="editor">
    {!! html_entity_decode($content) !!}
    </div>
    
  </div>
</body>
</html>