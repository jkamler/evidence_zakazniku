$("span.edit").click(function(event){
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
