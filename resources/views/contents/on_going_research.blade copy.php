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
                            <tfoot>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Author</th>
                                    <th style="text-align:center;">Title of Paper</th>
                                    <th style="text-align:center;">Keyword</th>
                                    <th style="text-align:center;">Files</th>
                                    <th style="text-align:center;">Status</th>
                                    <th style="text-align:center;">Action</th>
                                </tr>
                            </tfoot>
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

    let data = @json($data);
    let tr;
    let dt;
    console.log(data);
    show_tableRows();
    function show_tableRows(){
        let num= 0;
        data.forEach(arr => {
            let created_at = luxon.DateTime.fromISO(arr.created_at).toFormat('ff');
            let date_publication = luxon.DateTime.fromISO(arr.date_publication).toFormat('DD');
            num++;
            let tr = $('<tr class="text-center"></tr>');
            let fl = arr.file_names;
            if(isJson(fl)){
                fl = JSON.parse(fl);
            }
            let el='';
            el+='<td class="text-center">'+num+'</td>';
            el+='<td>'+arr.author+'</td>';
            el+='<td>'+arr.paper_title+'</td>';
            el+='<td>'+arr.keyword+'</td>';
            el+='<td class="text-center">';
            let first = true;
            fl.forEach(element => {
                if(first){
                    first = false;
                    el+='<a class="venobox" data-gall="'+arr.file_path+'" data-vbtype="iframe" href="/media/'+arr.file_path+'/'+element+'">Files ('+fl.length+')</a>';
                }
                else{
                    el+='<a class="venobox d-none" data-gall="'+arr.file_path+'" data-vbtype="iframe" href="/media/'+arr.file_path+'/'+element+'"></a>';
                }
            });
            el+='</td>';

            el+='<td></td>';
            el+='<td style="min-width:100px"align="center">';
            el+='<button class="btn btn-warning btn-sm btn-view" title="View Publication"><span class="fa fa-eye"></span></button> | ';
            el+='<button class="btn btn-danger btn-sm delete_research" title="View Publication"><span class="fas fa-trash-alt"></span></button>';
            el+='</td>';
            tr.data('index', num - 1);
            tr.append(el);
            $('#dataTable tbody').append(tr);
        });
    }
    $('#dataTable').on('click', '.btn-view', function(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        console.log(data[i])
        view_ongoing(data[i])
    })

    $('#dataTable').on('click', '.delete_research', function(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        console.log(data[i])
        ConfirmationModal('#confirmation_modal', {
            data: {
                id: data[i].id,
                file_path: data[i].file_path,
            },
            url: '/delete/ongoing',
            btn: 'Delete',
            btnClass: 'danger',
            message: 'Are you sure you want to delete <strong class="text-danger">'+data[i].paper_title+'</strong>?',
            done: function(){
                dt.row(tr).remove().draw();
            }
        });
    })

    function update_row(arr){
        let first = true;
        let fl = arr.file_names;
        let el = '';
        if(isJson(fl)){
            fl = JSON.parse(fl);
        }
        fl.forEach(element => {
            if(first){
                first = false;
                el+='<a class="venobox" data-gall="'+arr.file_path+'" data-vbtype="iframe" href="/media/'+arr.file_path+'/'+element+'">Files ('+fl.length+')</a>';
            }
            else{
                el+='<a class="venobox d-none" data-gall="'+arr.file_path+'" data-vbtype="iframe" href="/media/'+arr.file_path+'/'+element+'"></a>';
            }
        });
        let newRow = [$(tr).children('td').eq(0).html(), arr.author, arr.paper_title, arr.keyword, el, "",$(tr).children('td').eq(6).html()];
        dt.row(tr).data(newRow).draw();
    }
    $(document).ready( function () {
        dt = $('#dataTable').DataTable();
    } );

    $('.venobox').on('click', function(){
        var $iframe = $('.venoframe');
        $iframe.ready(function() {
            if($iframe.contents().find("body").html() == ''){
                $iframe.contents().find("body").append('Unsuported file');
            }
        });
    })
    
</script>
@endsection