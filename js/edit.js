/*
//tohle jsem nakonec zrusil, aby vsechny update byly udelany jednotne pres odeslani formulare

$("div.zmena_stavu").click(function(event){
  //value of id
  var id = event.target.id;
  //new value of state
  var value = prompt('Nový název stavu zákazníka');
  if (value == "" || value == null) {
    throw new Error();
  }
  var myURL = 'index.php?action=edit_stav&value=' + value + '&id=' + id
  $.ajax({
     type: 'GET',
     url: myURL,
  //   data: value,
     success: function(response){
       console.log(myURL);
       location.reload();
     }
  });
});
*/
