@extends('layouts.main')
@section('content')
<style>
    .doc{
        height: 80vh;
        width: 70%;
    }
</style>
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Presented Research</h1>
            <ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active"><i></i></li>
            </ol>
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>List of All Presented Research</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Date Presented</th>
                                    <th style="text-align:center;">Author(s)</th>
                                    <th style="text-align:center;">Title of Paper</th>
                                    <th style="text-align:center;">Files</th>
                                    <th style="text-align:center;">Status</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
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
        'pending-view': ['num', 'date_publication', 'author', 'paper_title', 'files', 'status', 'action'],
        'returned-view': ['num', 'date_publication', 'author', 'paper_title', 'files', 'status', 'remarks', 'action']
    }
    const viewTh = {
        'pending-view': ['No.','Date Presented', 'Author(s)', 'Title of Paper', 'Files', 'Status', 'Action'],
        'returned-view': ['No.','Date Presented', 'Author(s)', 'Title of Paper', 'Files', 'Status', 'Remarks', 'Action'],
    }

    init();
    function init(){
        let data = @json($data);
        console.log(data);
        let presented = new Research('presented', {
            data: data,
            owner: owner,
            view: view,
            cols: viewCols[view],
            main_title: 'paper_title',
        });
        presented.show_tableHead(viewTh[view]);
        presented.show_tableRows();
        presented.init_datatable();
    }
    
</script>
@endsection