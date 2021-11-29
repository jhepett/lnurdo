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
            <h1 class="mt-4">Published Research</h1>
            {{-- <iframe src="https://docs.google.com/gview?url=http://ieee802.org/secmail/docIZSEwEqHFr.doc&embedded=true" frameborder="0"></iframe> --}}
            {{-- <a href="{{asset('/storage/1/docs/4/h4I774HvEMR4hJGBdbgzenneUe9pVZrvZ7qCBsuo.docx')}}">asdds</a> --}}
            {{-- <iframe class="doc" src='https://view.officeapps.live.com/op/embed.aspx?src={{asset('/storage/1/docs/4/h4I774HvEMR4hJGBdbgzenneUe9pVZrvZ7qCBsuo.docx')}}' width='100%' height='100%' frameborder='0'>This is an embedded <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by <a target='_blank' href='http://office.com/webapps'>Office Online</a>.</iframe> --}}
            {{-- <a class="venobox" data-vbtype="iframe" href="{{asset('/storage/1/docs/4/YoD45e27ttP9nLGmganlGwVBoIbbDdm6TFqVIB4d.pdf')}}">open iFrame</a> --}}
            <ol class="breadcrumb mb-4">
				<li class="breadcrumb-item active"><i></i></li>
            </ol>
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>List of All Published Research</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Date Upload</th>
                                    <th style="text-align:center;">Research Title</th>
                                    <th style="text-align:center;">Author</th>
                                    <th style="text-align:center;">Date Published</th>
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
        'pending-view': ['num', 'created_at', 'publication_title', 'author', 'date_publication', 'files', 'status', 'action'],
        'returned-view': ['num', 'created_at', 'publication_title', 'author', 'date_publication', 'files', 'status', 'remarks', 'action']
    }
    const viewTh = {
        'pending-view': ['No.','Date Upload', 'Research Title', 'Author', 'Date Published', 'Files', 'Status', 'Action'],
        'returned-view': ['No.','Date Upload', 'Research Title', 'Author', 'Date Published', 'Files', 'Status', 'Remarks', 'Action'],
    }
    init();
    function init(){
        let data = @json($data);
        console.log(data);
        let publish = new Research('published', {
            data: data,
            owner: owner,
            view: view,
            cols: viewCols[view],
            main_title: 'publication_title',
        });
        publish.show_tableHead(viewTh[view]);
        publish.show_tableRows();
        publish.init_datatable();
    }
</script>
@endsection