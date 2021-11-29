@extends('layouts.main')
@section('content')
    <main>
        <div class="container-fluid">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-table mr-1"></i>Pending Accounts</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Contact No.</th>
                                    <th>Unit</th>
                                    <th>Position</th>
                                    <th>Date Requested</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Contact No.</th>
                                    <th>Unit</th>
                                    <th>Position</th>
                                    <th>Date Requested</th>
                                    <th>Action</th>
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
    let dt;
    let data = @json(@$users);
    console.log(data);
    show_tableRows();
    function show_tableRows(){
        let num= 0;
        data.forEach(arr => {
            num++;
            let created_at = luxon.DateTime.fromISO(arr.created_at).toFormat('ff');
            let tr = $('<tr></tr>');
            let el='';
            el+='<td class="text-center">'+num+'</td>';
            el+='<td>'+arr.email+'</td>';
            el+='<td>'+arr.name+'</td>';
            el+='<td>'+arr.contact_number+'</td>';
            el+='<td style="text-align:center;">'+arr.unit.unit+'</td>';
            el+='<td style="text-align:center;">'+arr.user_type.user_type+'</td>';
            el+='<td style="text-align:center;">'+created_at+'</td>';

            el+='<td style="min-width:100px"align="center">';
            el+='<button id="delete_user" class="btn btn-danger btn-sm btn-view"><i class="fas fa-trash-alt"></i></button> | ';
            el+='<button id="enable_user" class="btn btn-info btn-sm btn-view"><i class="fas fa-user-check"></i></button>';
            el+='</td>';

            tr.data('index', num - 1);
            tr.append(el);
            $('#dataTable tbody').append(tr);
        });
    }
    $('#dataTable').on('click', '#delete_user', function(){
        let tr = $(this).closest('tr');
        let i = tr.data('index');
        ConfirmationModal('#confirmation_modal', {
            data: {
                id: data[i].id,
            },
            url: '/user/delete',
            btn: 'Delete',
            btnClass: 'danger',
            message: 'Delete '+data[i].name+'?',
            done: function(){
                dt.row(tr).remove().draw();
            }
        });
    })
    $('#dataTable').on('click', '#enable_user', function(){
        let tr = $(this).closest('tr');
        let i = tr.data('index');
        ConfirmationModal('#confirmation_modal', {
            data: {
                id: data[i].id,
            },
            url: '/user/accept',
            btn: 'Enable',
            btnClass: 'info',
            message: 'Enable '+data[i].name+'?',
            done: function(){
                dt.row(tr).remove().draw();
            }
        });
    });

    $(document).ready( function () {
        dt = $('#dataTable').DataTable();
    });
</script>
@endsection