$(document).ready(function(){
 $( "#find" ).keyup(function(){
  fetch();
 });
});

function fetch()
{
 var val = document.getElementById( "find" ).value;
 $.ajax({
 type: 'post',
 url: 'index.php?action=klienti',
 data: {
  get_val:val
 },
 success: function (response) {
  document.getElementById( "result_table" ).innerHTML = response;
 }
 });
}
