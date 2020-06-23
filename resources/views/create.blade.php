<div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

        <h4 class="modal-title" id="myModalLabel">Create Item</h4>

      </div>

      <div class="modal-body">



      		<form data-toggle="validator" action="{{ route('posts.store') }}" method="POST">

      			<div class="form-group">

					<label class="control-label" for="title">Title:</label>

					<input type="text" name="title" class="form-control" data-error="Please enter title." required />

					<div class="help-block with-errors"></div>

				</div>

				<div class="form-group">

					<label class="control-label" for="title">Description:</label>

					<textarea name="details" class="form-control" data-error="Please enter details." required></textarea>

					<div class="help-block with-errors"></div>

				</div>

				<div class="form-group">

					<button type="submit" class="btn crud-submit btn-success">Submit</button>

				</div>

      		</form>

      </div>

    </div>

  </div>

</div>

<script>
var page = 1;

var current_page = 1;

var total_page = 0;

var is_ajax_fire = 0;




manageData();




/* manage data list */

function manageData() {



    $.ajax({

        dataType: 'json',

        url: url,

        data: {page:page}

    }).done(function(data){



    	total_page = data.last_page;

    	current_page = data.current_page;



    	$('#pagination').twbsPagination({

	        totalPages: total_page,

	        visiblePages: current_page,

	        onPageClick: function (event, pageL) {

	        	page = pageL;

                if(is_ajax_fire != 0){

	        	  getPageData();

                }

	        }

	    });



    	manageRow(data.data);

        is_ajax_fire = 1;

    });

}




$.ajaxSetup({

    headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

});




/* Get Page Data*/

function getPageData() {

	$.ajax({

    	dataType: 'json',

    	url: url,

    	data: {page:page}

	}).done(function(data){

		manageRow(data.data);

	});

}




/* Add new Post table row */

function manageRow(data) {

	var	rows = '';

	$.each( data, function( key, value ) {

	  	rows = rows + '<tr>';

	  	rows = rows + '<td>'+value.title+'</td>';

	  	rows = rows + '<td>'+value.details+'</td>';

	  	rows = rows + '<td data-id="'+value.id+'">';

        rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Edit</button> ';

        rows = rows + '<button class="btn btn-danger remove-item">Delete</button>';

        rows = rows + '</td>';

	  	rows = rows + '</tr>';

	});

	$("tbody").html(rows);

}




/* Create new Post */

$(".crud-submit").click(function(e){

    e.preventDefault();

    var form_action = $("#create-item").find("form").attr("action");

    var title = $("#create-item").find("input[name='title']").val();

    var details = $("#create-item").find("textarea[name='details']").val();



    $.ajax({

        dataType: 'json',

        type:'POST',

        url: form_action,

        data:{title:title, details:details}

    }).done(function(data){

        getPageData();

        $(".modal").modal('hide');

        toastr.success('Post Created Successfully.', 'Success Alert', {timeOut: 1000});

    });

});




/* Remove Post */

$("body").on("click",".remove-item",function(){

    var id = $(this).parent("td").data('id');

    var c_obj = $(this).parents("tr");



    $.ajax({

        dataType: 'json',

        type:'delete',

        url: url + '/' + id,

    }).done(function(data){



        c_obj.remove();

        toastr.success('Post Deleted Successfully.', 'Success Alert', {timeOut: 5000});

        getPageData();



    });

});




/* Edit Post */

$("body").on("click",".edit-item",function(){

    var id = $(this).parent("td").data('id');

    var title = $(this).parent("td").prev("td").prev("td").text();

    var details = $(this).parent("td").prev("td").text();



    $("#edit-item").find("input[name='title']").val(title);

    $("#edit-item").find("textarea[name='details']").val(details);

    $("#edit-item").find("form").attr("action",url + '/' + id);

});




/* Updated new Post */

$(".crud-submit-edit").click(function(e){

    e.preventDefault();

    var form_action = $("#edit-item").find("form").attr("action");

    var title = $("#edit-item").find("input[name='title']").val();

    var details = $("#edit-item").find("textarea[name='details']").val();



    $.ajax({

        dataType: 'json',

        type:'PUT',

        url: form_action,

        data:{title:title, details:details}

    }).done(function(data){

        getPageData();

        $(".modal").modal('hide');

        toastr.success('Post Updated Successfully.', 'Success Alert', {timeOut: 5000});

    });

});

</script>

