//searching customers
//$(document).ready(function(){

  //searching by fulltext
 $( "#find" ).keyup(function(){
   fetch_fulltext();
 });

//searching by selectList
 $( "#selectListStates" ).change(function(){
   fetch_states();
 });

 $(document).on('dblclick', '.poznamka', function(){
   $(".poznamka").prop("readonly", true);
   alert("ulozit<");
 });

$(document).on('focus', '.poznamka', function(event){
  if (event.which != 3) { //treti tlactko mysi
    alert('focus');
    $(".poznamka").prop("readonly", false);
  }

});

//$(document).on('focusout', '.poznamka', function(){
//  $(".poznamka").prop("readonly", true);
//  alert("ulozit<");
//});

$(document).on("contextmenu", ".poznamka", function(event){
  if (event.which == 3) {
    alert('Context Menu event has fired!');
  }
   return false;
});


//});

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

function fetch_states(val) {
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
