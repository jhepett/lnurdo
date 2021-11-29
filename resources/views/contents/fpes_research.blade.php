@extends('layouts.main')
@section('content')

@php
    $point_system = array();
    foreach (\App\Models\Involvement::all() as $inv){
        $point_system[$inv->id] = $inv->points;
    }
@endphp

    <main>
        <div class="container-fluid">
            <h1 class="mt-4">FRI Research</h1>
            <ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active"><i>positiion</i></li>
            </ol>
            <div class="card mb-4">
				<div class="card-header">
                    <i class="fas fa-table mr-1"></i>List of All Submitted FRI
                    <label class="float-right">Period of Evaluation: <b><i>{{date('M t, Y', strtotime($ep->from))}} - {{date('M t, Y', strtotime($ep->to))}}</i></b></label>
                </div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0"style="font-size:12px;">
							<thead class="text-center">
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
        </div>
    </main>   
@endsection

@section('script')
<script type="text/javascript">
    
    const owner = {{@$owner}};
    const view = '{{@$view}}';

    const viewCols = {
        'pending-view': ['num','evaluation_period', 'involvement', 'description', 'fpes_date', 'points', 'status', 'action'],
        'returned-view': ['num','evaluation_period', 'involvement', 'description', 'fpes_date', 'points', 'status', 'remarks', 'action'],
    }
    const viewTh = {
        'pending-view': ['No.','Evaluation Period', 'Involvement', 'Description/Title of Event', 'Date', 'Points Earned', 'Status', 'Action'],
        'returned-view': ['No.','Evaluation Period', 'Involvement', 'Description/Title of Event', 'Date', 'Points Earned', 'Status', 'Remarks', 'Action'],
    }

    init();
    function init(){
        let data = @json($data);
        console.log(data);
        
        data.forEach(arr => {
            for ( var key in arr ) {
                if(key == "involvement"){
                    arr["points"] = arr[key].points;
                    arr[key] = arr[key][key];
                    console.log("POINTS", arr[key].points);
                }
                else if(key == "evaluation_period"){
                    arr[key] = arr[key].from + " - " + arr[key].to;
                }
            }
        });
        
        console.log("RECONSTRUCTED DATA",data);
        let fpes = new Research('fpes', {
            data: data,
            owner: owner,
            view: view,
            main_title: 'description',
            cols: viewCols[view]
        });

        fpes.show_tableHead(viewTh[view]);
        fpes.show_tableRows();
        fpes.init_datatable();
        fpes.load_point_system(@json(@$point_system));
    }
    
    
    
</script>
@endsection