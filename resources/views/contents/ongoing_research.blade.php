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
            <h1 class="mt-4">On Going Research</h1>
            <ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active"><i></i></li>
            </ol>
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>List of All On Going Research</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Author</th>
                                    <th style="text-align:center;">Title of Paper</th>
                                    <th style="text-align:center;">Keyword</th>
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
        'pending-view': ['num', 'author', 'paper_title', 'keyword', 'files', 'status', 'action'],
        'returned-view': ['num', 'author', 'paper_title', 'keyword', 'files', 'status', 'remarks', 'action']
    }
    const viewTh = {
        'pending-view': ['No.','Author', 'Title of Paper', 'Keyword', 'Files', 'Status', 'Action'],
        'returned-view': ['No.','Author', 'Title of Paper', 'Keyword', 'Files', 'Status', 'Remarks', 'Action'],
    }
    init();
    function init(){
        let data = @json($data);
        console.log(data);
        let on_going = new Research('ongoing', {
            data: data,
            owner: owner,
            view: view,
            cols: viewCols[view],
            main_title: 'paper_title',
        });
        on_going.show_tableHead(viewTh[view]);
        on_going.show_tableRows();
        on_going.init_datatable();
    }
</script>
@endsection