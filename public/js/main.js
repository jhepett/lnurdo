// RESEARCH STATUS
// 5 - pending (at URC)
// 6 - pending (at CRC)
// 7 - pending (at VPRC)

// 25 - returned from URC to Teacher
// 56 - returned from CRC to URC
// 67 - returned from VPRC to CRC

function Research(type, opts){
    console.log('RESEARCH INIT');
    let dt;
    let tr;
    let data = opts.data;
    let owner = opts.owner;
    let cols = opts.cols;
    let main_title = opts.main_title;
    let point_system = {};
    let status = {
        3: (owner) ? "To be checked by URC": "Pending",
        4: (owner) ? "To be checked by CRC": "Pending",
        5: (owner) ? "Submitted to RDO": "Pending",
        23: "Returned by URC",
        34: (owner) ? "Returned by CRC to URC":"Returned by CRC",
        45: (owner) ? "Returned by RDO to CRC":"Returned by RDO",
        6: "Confirmed by RDO",
    }
    this.load_point_system=(ps)=>{
        point_system = ps;
    }
    this.show_tableHead=(thView)=>{
        let tr = $('<tr></tr>');
        thView.forEach(th => {
            tr.append('<th>'+th+'</th>');
                console.log(th)
        });
        $('#dataTable thead').html(tr);
    }
    this.show_tableRows=()=>{
        let num= 0;
        data.forEach(arr => {
            num++;
            let tr = $('<tr class="text-center"></tr>');
            let fl;
            if(arr.file_names !== undefined){
                fl = JSON.parse(arr.file_names);
            }
            let el='';
            cols.forEach(col => {
                if(col == "num"){
                    el+='<td class="text-center">'+num+'</td>';
                }
                else if(col == "created_at"){
                    let created_at = luxon.DateTime.fromISO(arr.created_at).toFormat('ff');
                    el+='<td>'+created_at+'</td>';
                }
                else if(col == "date_publication"){
                    let date_publication = luxon.DateTime.fromISO(arr.date_publication).toFormat('DD');
                    el+='<td>'+date_publication+'</td>';
                }
                else if(col == "status"){
                    el+='<td>'+status[arr.status]+'</td>';
                }
                else if(col == "fpes_date"){
                    el+='<td>'+arr.from+" - "+arr.to+'</td>';
                }
                else if(col == "files"){
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
                }
                else if(col == "action"){
                    el+='<td style="min-width:100px"align="center">';
                    if(view == "pending-view"){
                        if(owner){
                            el+='<button class="btn btn-warning btn-sm btn-view" title="View Publication"><span class="fa fa-eye"></span></button> | ';
                            el+='<button class="btn btn-danger btn-sm delete_research" title="Delete research"><span class="fas fa-trash-alt"></span></button>';
                        }
                        else{
                            el+='<button class="btn btn-warning btn-sm btn-view" title="View Publication"><span class="fa fa-eye"></span></button> | ';
                            el+='<button class="btn btn-success btn-sm add_status_research" title="Accept/Return research"><span class="far fa-edit"></span></button>';
                        }
                    }
                    else if(view == "returned-view"){
                        if(owner){
                            el+='<button class="btn btn-warning btn-sm btn-view" title="View Publication"><span class="fa fa-eye"></span></button> | ';
                            el+='<button class="btn btn-danger btn-sm delete_research" title="Delete research"><span class="fas fa-trash-alt"></span></button> | ';
                            el+='<button class="btn btn-success btn-sm resubmit_research" title="Resubmit research"><span class="far fa-share-square"></span></button>';
                        }
                        else{
                            el+='<button class="btn btn-warning btn-sm btn-view" title="View Publication"><span class="fa fa-eye"></span></button> | ';
                            el+='<button class="btn btn-info btn-sm add_status_research" title="Accept/Return research"><span class="far fa-edit"></span></button> | ';
                            el+='<button class="btn btn-success btn-sm resubmit_research" title="Resubmit research"><span class="far fa-share-square"></span></button>';
                        }
                    }
                    el+='</td>';
                }
                else{
                    if(arr[col] == null){
                        el+='<td></td>';
                    }
                    else{
                        el+='<td>'+arr[col]+'</td>';
                    } 
                }
            });
            tr.data('index', num - 1);
            tr.append(el);
            $('#dataTable tbody').append(tr);
        });
    }
    this.init_datatable=()=>{
         dt = $('#dataTable').DataTable( {
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]
        } );         
        dt.on( 'order.dt search.dt', function () {
            dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    }

    let update_row=(arr)=>{
        console.log('UPDATE')
        let fl;
        let new_data = [];
        if(arr.file_names !== undefined){
            fl = arr.file_names;
            if(isJson(fl)){
                fl = JSON.parse(fl);
            }
        }

        cols.forEach(col => {
            if(col == "num"){
                new_data.push(1);
            }
            else if(col == "date_publication"){
                let date_publication = luxon.DateTime.fromISO(arr.date_publication).toFormat('DD');
                new_data.push(date_publication);
            }
            else if(col == "created_at"){
                let created_at = luxon.DateTime.fromISO(arr.created_at).toFormat('ff');
                new_data.push(created_at);
            }
            else if(col == "files"){
                let el = '';
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
                new_data.push(el);
            }
            else if(col == "action"){
                new_data.push($(tr).children('td').eq(cols.length - 1).html());
            }
            else if(col == "status"){
                new_data.push(status[arr.status]);
            }
            else if(col == "fpes_date"){
                new_data.push(arr.from+" - "+arr.to);
            }
            else if(col == "points"){
                new_data.push(point_system[arr.involvement_id]);
            }
            else{
                new_data.push(arr[col]);
            }
        });
        dt.row(tr).data(new_data).draw();
        $('.venobox').venobox({
            framewidth : '80%',
            frameheight: '90vh',
            share      : ['download']
        }); 
    }

    $('#dataTable').on('click', '.btn-view', btn_view_func);
    $('#dataTable').on('click', '.resubmit_research', resubmit_research);
    if(owner){
        $('#dataTable').on('click', '.delete_research', btn_delete_func);
    }
    else{
        $('#dataTable').on('click', '.add_status_research', verify_action);
        $('#'+type+'_research_modal input, '+'#'+type+'_research_modal select, '+'#'+type+'_research_modal textarea').prop('disabled', 'true');
        $('#'+type+'_research_modal #inputGroupFile01').closest('.form-group').remove();
        $('#'+type+'_research_modal #btn_pos').remove();
    }
    function verify_action(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        $('#for_verify_modal').modal('show');
        $('#for_verify_modal #verify_action').off('change');
        $('#for_verify_modal #verify_action').on('change', function(){
            if($(this).val()=="2"){
                $(this).next('div').html('<label class="text-dark">Remarks:</label><textarea id="verify_remarks" class="form-control" rows="3" placeholder="Write here..."></textarea>');
            }
            else{
                $(this).next('div').html('');
            }
        });
        $('#for_verify_modal #verify_btn').on('click', function(){
            let act = $('#for_verify_modal #verify_action').val();
            let error = false;
            let m = "accept";
            let params = {
                id: data[i].id,
                action: act
            }
            if(act == 2){
                let remark = $('#for_verify_modal #verify_remarks').val();
                params['remarks'] = remark;
                m = "return"; 
                if(remark == "" || remark === undefined){
                    error = true;
                    toastr.error('Please add remarks before you return the paper.');
                }
            }
            if(!error){
                 $('#for_verify_modal').modal('hide');
                ConfirmationModal('#confirmation_modal', {
                    data: params,
                    url: '/update/'+type+'/status',
                    btn: 'Proceed',
                    btnClass: 'warning',
                    message: 'Are you sure you want to '+m+' this paper?',
                    done: function(res){
                        console.log('RESULT', res);
                        dt.row(tr).remove().draw();
                    }
                });
            }

        });
    }
    function resubmit_research(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        let params = {
            id: data[i].id,
            action: 1
        }
        ConfirmationModal('#confirmation_modal', {
            data: params,
            url: '/update/'+type+'/status',
            btn: 'Proceed',
            btnClass: 'warning',
            message: 'Are you sure you want to resubmit '+data[i][main_title]+'?',
            done: function(res){
                console.log('RESULT', res);
                dt.row(tr).remove().draw();
            }
        });    
    }
    function btn_delete_func(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        console.log(data[i])
        ConfirmationModal('#confirmation_modal', {
            data: {
                id: data[i].id,
                file_path: data[i].file_path,
            },
            url: '/delete/'+type,
            btn: 'Delete',
            btnClass: 'danger',
            message: 'Are you sure you want to delete <strong class="text-danger">'+data[i][main_title]+'</strong>?',
            done: function(){
                dt.row(tr).remove().draw();
            }
        });
    }
    function btn_view_func(){
        tr = $(this).closest('tr');
        let i = $(this).closest('tr').data('index');
        view_research(data[i]);
    }
    function view_research(data){
        let new_files = [];
        let fl = data.file_names;
        let prepareModalData = {
            published: function(){
                $('#published_research_modal [name="author"]').val(data.author);
                $('#published_research_modal [name="publication_title"]').val(data.publication_title);
                $('#published_research_modal [name="date_publication"]').val(data.date_publication);
                $('#published_research_modal [name="journal_title"]').val(data.journal_title);
                $('#published_research_modal [name="vol_no"]').val(data.vol_no);
                $('#published_research_modal [name="issn"]').val(data.issn);
                $('#published_research_modal [name="site"]').val(data.site);
                $('#published_research_modal [name="pages"]').val(data.pages);
                $('#published_research_modal [name="indexing"]').val(data.indexing);
                $('#published_research_modal [name="keyword"]').val(data.keyword);
                $('#published_research_modal [name="school_year"]').val(data.school_year);
                $('#published_research_modal [name="semester"]').val(data.semester);
                $('#published_research_modal [name="unit_id"]').val(data.unit_id);
                $('#published-files-uploaded').html('');
            },
            presented: function(){
                $('#presented_research_modal [name="author"]').val(data.author);
                $('#presented_research_modal [name="paper_title"]').val(data.paper_title);
                $('#presented_research_modal [name="title"]').val(data.title);
                $('#presented_research_modal [name="venue"]').val(data.venue);
                $('#presented_research_modal [name="organizer"]').val(data.organizer);
                $('#presented_research_modal [name="locale"]').val(data.locale);
                $('#presented_research_modal [name="date_publication"]').val(data.date_publication);
                $('#presented-files-uploaded').html('');
            },
            completed: function(){
                $('#completed_research_modal [name="author"]').val(data.author);
                $('#completed_research_modal [name="paper_title"]').val(data.paper_title);
                $('#completed_research_modal [name="date_publication"]').val(data.date_publication);
                $('#completed_research_modal [name="keyword"]').val(data.keyword);
                $('#completed_research_modal [name="semester"]').val(data.semester);
                $('#completed-files-uploaded').html('');
            },
            ongoing: function(){
                $('#ongoing_research_modal [name="author"]').val(data.author);
                $('#ongoing_research_modal [name="paper_title"]').val(data.paper_title);
                $('#ongoing_research_modal [name="date_publication"]').val(data.date_publication);
                $('#ongoing_research_modal [name="keyword"]').val(data.keyword);
                $('#ongoing_research_modal [name="semester"]').val(data.semester);
                $('#ongoing_research_modal [name="research_deliverable"]').val(data.research_deliverable);
                $('#ongoing-files-uploaded').html('');
            },
            fpes: function(){
                $('#fpes_research_modal [name="involvement_id"]').val(data.involvement_id);
                $('#fpes_research_modal [name="description"]').val(data.description);
                $('#fpes_research_modal [name="from"]').val(data.from);
                $('#fpes_research_modal [name="to"]').val(data.to);
            }   
        }
        prepareModalData[type]();
        if(fl){
            if(isJson(fl)){
                fl = [...JSON.parse(fl)];
            }
            fl.forEach(file => {
                displayFiles('#'+type+'-files-uploaded', file);
            });
        }
        $('#'+type+'_research_modal').modal('show');

        $('.custom-file-input').off('change');
        $('#'+type+'-files-uploaded').off('click', '.fa-times');
        $('#update_'+type+'_form').off('submit');


        $('.custom-file-input').on('change',function() {
            var files = $(this)[0].files;
            for (let i = 0; i < files.length; i++) {
                if(!fl.includes(files[i].name)){
                    new_files.push({filename:files[i].name, file:files[i]}); 
                    displayFiles('#'+type+'-files-uploaded', files[i].name);
                    fl.push(files[i].name);
                }
                else{
                    toastr.error(files[i].name+" already added");
                    console.log('File already added')
                }
            }
            console.log(fl);
        });
        $('#'+type+'-files-uploaded').on('click', '.fa-times', function(){
            let parent = $(this).closest('.col-4');
            let index = parent.index();
            let n_i = new_files.findIndex(nf => nf.filename == fl[index]);
            console.log("n_i", n_i);
            if(n_i !== undefined && n_i >= 0){
                new_files.splice(n_i, 1);
            }
            fl.splice(index, 1);
            parent.remove();
        });
        $('#update_'+type+'_form').on('submit', function(e){
            if(fl){
                data.file_names = fl;
            }
            e.preventDefault();
            var formData = new FormData();
            for ( var key in data ) {
                if($('#'+type+'_research_modal [name="'+key+'"]').length > 0){
                    data[key] = $('#'+type+'_research_modal [name="'+key+'"]').val() || data[key];
                }
                if(key == "file_names"){
                    formData.append(key, JSON.stringify(data[key]));
                }
                else{
                    formData.append(key, data[key]);
                }
                
            }

            new_files.forEach(nf => {
                formData.append("file[]", nf.file);
            });
                
            $.ajax({
                url         : '/update/'+type,
                data        : formData,
                processData : false,
                contentType : false,
                type: 'POST'
            }).done(function(result){
                if(fl){
                    data.file_names = [...fl];
                }
                toastr.success("Updated");
                $('#'+type+'_research_modal').modal('hide');
                update_row(data)
            });   
        });
    }

    function displayFiles(html_con,filename){
        let ext = filename.substring(filename.lastIndexOf(".")+1); 
        let icon = '<i class="fas fa-file fa-2x text-info"></i>';
        if(ext == 'docx' || ext == 'doc'){
            icon = '<i class="fas fa-file-word fa-2x text-primary"></i>';
        }  
        else if(ext == 'pdf'){
            icon = '<i class="fas fa-file-pdf fa-2x text-danger"></i>';
        }
        else if(ext == 'zip'){
            icon = '<i class="fas fa-file-archive fa-2x text-warning"></i>';
        }

        console.log(ext)
        let el = '';
        el += '<div class="col-4 my-2">';
        el += '<div class="d-flex flex-column align-items-center">';
        if(owner){
            el += '<i class="fas fa-times"></i>';
        }
        el += icon;
        el += '<small class="w-100 px-1 text-center text-truncate">'+filename+'</small>';
        el += '</div>';
        el += '</div>';
        $(html_con).append(el);
    }

}
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}         
