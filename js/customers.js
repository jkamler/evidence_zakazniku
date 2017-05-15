//searching customers
$(document).ready(function(){
  //searching by fulltext
 $( "#find" ).keyup(function(){
  fetch();
 });
//searching by selectList
 $( "#selectListStates" ).change(function(){
   var val = document.getElementById( "selectListStates" ).value;
   alert(val);
//  fetch();
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


/*
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
*/
