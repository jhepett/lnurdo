@extends('layouts.main')
@section('content')
@php
    $involvements = \App\Models\Involvement::all();
@endphp
	<style>
		#fpes .form-control{
			background: transparent;
			border:solid 1px #54705F;
		}
		#fpes .form-control:focus{
			background: #ffffff !important;
		}
		#fpes th{
			font-size: 80%;
		}
		#fpes tbody tr:nth-child(odd){
			/* background-color:#f5eed8 !important; */
		}
		#fpes tbody tr td{
			vertical-align: middle;
		}
		.was-validated .form-control:invalid, .form-control.is-invalid {
			border-color: #dc3545 !important;
			padding-right: .5rem !important;
		}

	</style>
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Upload Research</h1>
            <ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active"><i>Admin</i></li>
				<div class="ml-auto" style="">
				</div>
            </ol>
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i> Upload Research</div>
                <div class="card-body">
                    <div class="table-responsive">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-upload"></i> Published Research</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-upload"></i> Presented Research</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-upload"></i> Completed Research</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact1" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-upload"></i> On-Going Research</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="contact-tab" data-toggle="tab" href="#fpes" role="tab" aria-controls="contact" aria-selected="false"><i class="fa fa-bars"></i> FRI</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
								<br/>
								<form action="/save/published" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="form-group">
										<input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="publ_title" placeholder="Publication Tittle" title="Publication Title" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="jrnl_title" placeholder="Journal Tittle" title="Journal Title"required>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-3 pl-lg-0 p-0">
											<input class="form-control" name="vol_no" placeholder="Vol.No./Issue" title="Vol.no./Issue"required>
										</div>
										<div class="form-group col-md-3 px-lg-2 p-0">
											<input class="form-control" name="issn" placeholder="ISSN" title="ISSN"required>
										</div>
										<div class="form-group col-md-4 px-lg-2 p-0">
											<input class="form-control" name="site" placeholder="Site" title="Site"required>
										</div>
										<div class="form-group col-md-2 pr-lg-0 p-0">
											<input class="form-control" name="page" placeholder="Pages" required>
										</div>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-4 pl-lg-0 p-0">
											<input class="form-control" name="index" placeholder="Indexing" required>
										</div>
										<div class="form-group col-md-4 px-lg-2 p-0">
											<input class="form-control" name="keyword" placeholder="Keyword" required>
										</div>
										<div class="form-group col-md-4 pr-lg-0 p-0">
											<input class="form-control" name="school_year" placeholder="School Year" required>
										</div>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<select class="form-control" name="sem" placeholder="Semester" required>
												<option value="" selected hidden>SELECT SEMESTER</option>
												<option value="1">1st Semester</option>
												<option value="2">2nd Semester</option>
											</select>
										</div>
										<div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
											<select class="form-control" name="college_unit" placeholder="College/Unit" required>
												<option selected value="" hidden>SELECT COLLEGE/UNIT</option>
												<option value="">None</option>
												@foreach ($units as $unit)
													<option value="{{$unit->id}}">{{$unit->unit}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="input-group mb-3 col-md-6 p-0">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple required>
											<label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
										</div>
									</div>

									<hr>
									<div class="d-flex justify-content-end">
										<div class="col-md-4 p-0">
											<div class="form-group date_publication_con">
												<label for="exampleInputEmail1">Date Published:</label>
												<input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication"required >
												<button type="submit" class="btn btn-success float-right ml-3" name="upload"><span class="fa fa-upload"></span></button>
											</div>
										</div>
									</div>
									{{-- <button type="submit" class="btn btn-success float-right" style="border:solid 1px #54705F;margin-left:0.6%"name="upload"><span class="fa fa-upload"></span></button>
									<input class="btn float-right" style="border:solid 1px #54705F;width:32%;margin-left:0.6%" name="date_publication" type="date" placeholder="Date of Publication"required >
									<label class="float-right"style="margin-top:0.6%">Date Published: </label> --}}
								</form>
							</div>
							<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
								<br/>
								<form action="/save/presented" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="form-group">
										<input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Title, Venue and Date of Conference Where the Research Output Was Presented:</label>
										<input class="form-control" name="title" placeholder="Title" title="Title"required>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<input class="form-control" name="venue" placeholder="Venue" required>
										</div>
										<div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
											<input class="form-control" name="organizer" placeholder="Organizer" required>
										</div>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<select class="form-control" name="locale" required>
												<option value="" selected hidden>Intl. or Local</option>
												<option value="intl">International</option>
												<option value="local">Local</option>
											</select>
										</div>
										<div class="input-group col-md-6 pr-lg-0 pl-lg-1 p-">
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple required>
												<label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
											</div>
										</div>
									</div>
									<hr>
									<div class="d-flex justify-content-end">
										<div class="col-md-4 p-0">
											<div class="form-group date_publication_con">
												<label for="exampleInputEmail1">Date Published:</label>
												<input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication"required >
												<button type="submit" class="btn btn-success float-right ml-3" name="upload"><span class="fa fa-upload"></span></button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
								<br/>
								<form action="/save/completed" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="form-group">
										<input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<input class="form-control" name="keyword" placeholder="Keyword" required>
										</div>
										<div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
											<select class="form-control" name="sem" placeholder="Semester" required>
												<option value="" selected hidden>SELECT SEMESTER</option>
												<option value="1">1st Semester</option>
												<option value="2">2nd Semester</option>
											</select>
										</div>
									</div>
									<div class="input-group mb-3 col-md-6 p-0">
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple required>
											<label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
										</div>
									</div>

									<hr>
									<div class="d-flex justify-content-end">
										<div class="col-md-4 p-0">
											<div class="form-group date_publication_con">
												<label for="exampleInputEmail1">Date Published:</label>
												<input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication"required >
												<button type="submit" class="btn btn-success float-right ml-3" name="upload"><span class="fa fa-upload"></span></button>
											</div>
										</div>
									</div>
									{{-- <button type="submit" class="btn btn-success float-right" style="border:solid 1px #54705F;margin-left:0.6%"name="upload"><span class="fa fa-upload"></span></button>
									<input class="btn float-right" style="border:solid 1px #54705F;width:32%;margin-left:0.6%" name="date_publication" type="date" placeholder="Date of Publication"required >
									<label class="float-right"style="margin-top:0.6%">Date Published: </label> --}}
								</form>
							</div>
							<div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab">
								<br/>
								<form action="/save/ongoing" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="form-group">
										<input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
									</div>
									<div class="form-group">
										<input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<input class="form-control" name="keyword" placeholder="Keyword" required>
										</div>
										<div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
											<select class="form-control" name="sem" placeholder="Semester" required>
												<option value="" selected hidden>SELECT SEMESTER</option>
												<option value="1">1st Semester</option>
												<option value="2">2nd Semester</option>
											</select>
										</div>
									</div>
									<div class="row m-0">
										<div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple required>
												<label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
											</div>
										</div>
										<div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
											<select class="form-control" name="research_deliverable" required>
												<option value="" selected hidden>Research/Deliverables</option>
												<option value="Concept Paper">Concept Paper</option>
												<option value="Proposal">Proposal</option>
												<option value="Full Paper">Full Paper</option>
											</select>
										</div>	
									</div>
									<hr>
									<div class="d-flex justify-content-end">
										<div class="col-md-4 p-0">
											<div class="form-group date_publication_con">
												<label for="exampleInputEmail1">Date Published:</label>
												<input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication"required >
												<button type="submit" class="btn btn-success float-right ml-3" name="upload"><span class="fa fa-upload"></span></button>
											</div>
										</div>
									</div>
								</form>
							</div><br/>
							<div class="tab-pane fade" id="fpes" role="tabpanel" aria-labelledby="contact-tab">
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-1">Involvement</th>
												<th class="col-5">Description/Title of Event</th>
												<th class="col-1">Date</th>
												<th class="text-center col-1">Action</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
								<hr>
								<div class="my-4 mx-0 d-flex justify-content-between">
									<div class="m-0 row">
										<input type="number" min="1" max="50" value="1" class="form-control text-center form-control-sm mx-2" style="width: 4rem;">
										<button class="btn btn-sm btn-secondary" id="add_row">Add</button>
									</div>
									<div>
										<button class="btn btn-success btn-sm" id="submit_fpes">Send</button>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
<script type="text/javascript">
	@if(session()->get( 'data' ) == 'success')
		toastr.success('Success!');
	@endif
	let involvements = @json(@$involvements);
    $(document).ready( function () {
        $('#myTable').DataTable();
    });
	$('.custom-file-input').on('change',function(){
		var fileName = $(this).val();
		var names = [];
		for (var i = 0; i < $(this).get(0).files.length; ++i) {
			names.push($(this).get(0).files[i].name);
		}
		$(this).next('.custom-file-label').html(names.join(', '));
	})

	$('#add_row').on('click', function(){
		let num = parseInt($(this).prev('input').val()) || 0;
		if(num < 51){
			for (let i = 0; i < num; i++) {
				generate_row();	
			}
			$(this).prev('input').val(1)
		}
		else{
			toastr.error('Max row (50) exceeded.');
		}
	});
	$('#fpes').on('click','.delete_fpes', function(){
		let tr = $(this).closest('tr');
		if($('#fpes tbody > tr').length !== 1){
			$(tr).remove();
		}
		else{
			$('.toast').remove();
			toastr.error("You can't delete the last row.");
		}
		
	});


	generate_row();
	function generate_row(){
		let tr = $('<tr></tr>');
		let td = '';
		td += '<td>';
		td += '<select class="form-control form-control-sm" name="invl" required>';
		td += '<option value="" hidden></option>';
		involvements.forEach(involvement => {
			td += '<option value="'+involvement.id+'">'+involvement.involvement+'</option>';
		});
		td += '</select>';
		td += '</td>';
		td += '<td>';
		td += '<textarea class="form-control" name="desc"  autofocus required></textarea>';
		td += '</td>';
		td += '<td>';
		td += '<small>From:</small>';
		td += '<input class="form-control form-control-sm" type="date" name="from" required>';
		td += '<small>To:</small>';
		td += '<input class="form-control form-control-sm" type="date" name="to" required>';
		td += '</td>';
		td += '<td align="center">';
		td += '<button class="btn btn-danger btn-sm delete_fpes" title="View Publication"><span class="fas fa-trash-alt"></span></button>';
		td += '</td>';
		tr.html(td);
		$('#fpes tbody').append(tr);
	}


	$('#submit_fpes').on('click', function(){
		$('#submit_fpes').prop('disabled', true);
		let fpes = [];
		let error = false;
		let request;

		let check_empty=(input)=>{
			if(input.val() == ""){
				input.addClass('is-invalid');
				error = true;
			}
			else{
				input.removeClass('is-invalid');
			}
		}

		$('#fpes tbody > tr').each(function(){
			let invl = $(this).find('[name="invl"]');
			let desc = $(this).find('[name="desc"]');
			let from = $(this).find('[name="from"]');
			let to = $(this).find('[name="to"]');
			check_empty(invl);
			check_empty(desc);
			check_empty(from);
			check_empty(to);
			let data = {
				involvement_id: invl.val(),
				description: desc.val(),
				from: from.val(),
				to: to.val()
			}
			fpes.push(data);
		});
		if(!error && fpes.length > 0){
			console.log("fpes",fpes);
			if (request) {
				toastr.info('Please wait...');
				request.abort();
			}
			
			request = $.ajax({
				url: '/save/fpes',
				type: "post",
				data: {_token:"{{csrf_token()}}",fpes: JSON.stringify(fpes)}
			});
			request.done(function(result){
				toastr.success('Uploaded');
				$('#fpes tbody').html('');
				fpes = [];
				generate_row();
				console.log("Result", result);
			});
			request.fail(function (jqXHR, textStatus, errorThrown){
				toastr.error('Something went wrong');
				console.error(
					"The following error occurred: "+
					textStatus, errorThrown
				);
			});
			request.always(function () {
				$('#submit_fpes').prop('disable', 'false');
			});
		}
	});
</script>
@endsection