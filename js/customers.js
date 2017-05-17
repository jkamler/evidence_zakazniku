//searching customers

/*
nemuzu mit document ready protoze ready je jen pri prvnim nacteni a pak mi ty funkce nejedou
jakmile to nactu AJAXem
*/
//$(document).ready(function(){

  //searching by fulltext
 $( "#find" ).keyup(function(){
   fetch_fulltext();
 });

//searching by selectList
 $( "#selectListStates" ).change(function(){
   fetch_states();
 });

 function fetch_fulltext() {
   var val_ful = document.getElementById( "find" ).value;
   var val_sel = document.getElementById( "selectListStates" ).value;
   $.ajax({
     type: 'post',
     url: 'index.php?action=klienti',
     data: {
       fulltext_val:val_ful,
       states_val:val_sel
     },
     success: function (response) {
       document.getElementById( "result_table" ).innerHTML = response;
     }
   });
 }

 function fetch_states() {
   var val_ful = document.getElementById( "find" ).value;
   var val_sel = document.getElementById( "selectListStates" ).value;
   $.ajax({
     type: 'post',
     url: 'index.php?action=klienti',
     data: {
       fulltext_val:val_ful,
       states_val:val_sel
   },
   success: function (response) {
     document.getElementById( "result_table" ).innerHTML = response;
   }
  });
 }
