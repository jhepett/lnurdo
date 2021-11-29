@extends('layouts.main')
@section('content')
@php
    $colleges = \App\Models\College::all();
    $units = \App\Models\Unit::all();
@endphp
<style>
    .folder{
        border: solid rgba(95,99,104, .42) 1px;
        border-radius: 5px;
        color: #5F6368 !important;
        margin-top: 1.5rem;
    }
    .folder:hover{
        cursor: pointer;
        color: #ffffff !important;
        background: #5F6368;
    }
    .folder > p{
        font-weight: 600;
    }
    .file-tree:hover{
        cursor: pointer;
        color: lightskyblue !important;
    }
    .loader {
        border: .2rem solid #f3f3f3;
        border-radius: 50%;
        border-top: .2rem solid #3498db;
        width: 1rem;
        height: 1rem;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
        display: inline-block;
    }

        /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
    <main>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h1 class="m-0">Files</h1>
                <div class="col-3" >
                    <input type="text" name="" id="searchQuery" class="form-control" placeholder="Search">
                </div>
            </div>
            <hr class="mb-0">
            <div>
                <nav class="main-header navbar navbar-expand navbar-white navbar-light p-0 mb-3">
                    <ul class="navbar-nav" id="file-tree-con">
                    </ul>
                </nav>                
            </div>
            <div id="folder_container" class="row mb-5">
            </div>
            <hr>
            <div id="table_container" class="mt-5">
                <div class="d-flex justify-content-end mb-3">
                    <a class="btn btn-primary text-light" id="download_files" download><i class="fas fa-download"></i> Download files in this folder</a>
                    <div style="display: none"><div class="btn btn-secondary d-flex align-items-center"> <div class="loader"></div> <span class="ml-2">Zipping...</span></div></div>
                </div>
                <div class="table-responsive">
						<table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0"style="font-size:12px;">
							<thead class="text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Files</th>
                                    <th>Type</th>
                                    <th>Date Submitted</th>
                                </tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
            </div>
        </div>
    </main>
@endsection
@section('script')
    <script>
        const user_type = {{Auth::user()->user_type_id}};
        var touchtime = 0;
        var delay = 800;
        var action = null;
        let steps = [];
        let query = [];
        let files = {};
        let r_type;
        let request;
        let downloadRequest;
        let prevSearch = '';
        $('#folder_container').on('click', '.folder-con', nextFolder);
        addNavLink('Home');
         
        let collegeFolder = new CollegeFolder();
        let unitfolder = new UnitFolder();
        if(user_type === 5){
            collegeFolder.displayAll();
            steps.push('collegeFolder.displayAll()');
        }
        else if(user_type === 4){
            collegeFolder.displaybyId({{Auth::user()->college_id ?: 0}});
            steps.push('collegeFolder.displaybyId('+{{Auth::user()->college_id ?: 0}}+')');
            query.push('college_id','{{Auth::user()->college_id ?: 0}}');
        }
        else if(user_type === 3){
            unitfolder.displaybyId({{Auth::user()->unit_id ?: 0}});
            steps.push('unitfolder.displaybyId('+{{Auth::user()->unit_id ?: 0}}+')');
            query.push('unit_id', '{{Auth::user()->unit_id ?: 0}}');
        }
        fetchFiles();

        function nextFolder(){
            /*Double Click */
            if((new Date().getTime() - touchtime) < delay){
                $('#folder_container').show();
                clearTimeout(action)
                touchtime=0;
                let type = $(this).data('type');
                let id = $(this).data('id');
                // console.log('type', 'unitfolder.displaybyCollegeId('+id+')');
                if(type == 'college'){ 
                    steps.push('unitfolder.displaybyCollegeId('+id+')');
                    query.push(['college_id', id]);
                    unitfolder.displaybyCollegeId(id);
                }
                else if(type == 'unit'){
                    steps.push('defaultFolders()');
                    query.push(['unit_id', id]);
                    defaultFolders();
                }
                else if(type == 'default'){
                    r_type = id;
                    $('#folder_container').hide();
                }
                addNavLink($(this).text());
            }
            /* Single Click */
            else{
                touchtime = new Date().getTime();
                action = setTimeout(function(){
                },delay);
            }
            fetchFiles();
        }
        function CollegeFolder(){
            let colleges = @json($colleges);
            this.displaybyId=(id)=>{
                let arr = colleges.filter(c => c.id == id);
                displayFolder(arr, 'college');
            }
            this.displayAll=()=>{
                displayFolder(colleges, 'college');
            }
        }
        function UnitFolder(){
            let units = @json($units);
            this.displaybyId=(id)=>{
                let arr = units.filter(u => u.id == id);
                displayFolder(arr, 'unit');
            }
            this.displaybyCollegeId=(id)=>{
                let arr = units.filter(u => u.college_id == id);
                displayFolder(arr, 'unit');
            }
            this.displayAll=()=>{
                displayFolder(units, 'unit');
            }
        }
        function defaultFolders(){
            let data = [
                {
                    id: 1,
                    default: "Published"
                },
                {
                    id: 2,
                    default: "Presented"
                },
                {
                    id: 3,
                    default: "Completed"
                },
                {
                    id: 4,
                    default: "Ongoing"
                },
                {
                    id: 5,
                    default: "Fpes"
                },
            ];
            displayFolder(data, 'default');
        }
        function displayFolder(data, type){
            $('#folder_container').html('');
            data.forEach(d => {
                let div = $('<div class="folder-con col-3"></div>');
                let el = '';
                el+='<div class="folder p-3 d-flex align-items-center">';
                el+='<i class="fas fa-folder fa-lg"></i>';
                el+='<p class="my-0 ml-3 w-100 text-truncate">'+d[type]+'</p>';
                el+='</div>';
                div.data('type', type);
                div.data('id', d.id);
                div.html(el);    
                $('#folder_container').append(div);
            });
        }
        function addNavLink(text){
            $('#file-tree-con').append('<li class="nav-item"><a class="nav-link file-tree">'+text+'</a></li>')
            $('#file-tree-con').append('<li class="nav-item"><a class="nav-link">></a></li>')
        }
        function fetchFiles(sQuery){
            let dataQuery = {
                where: query,
                searchQuery: sQuery || '',
                type: r_type
            }
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: window.location.origin+'/search/files',
                type: "post",
                data: dataQuery,
            });
            request.done(function(result){
                console.log("RESULT",result);
                files = result;
                show_files();
            });
            request.fail(function (jqXHR, textStatus, errorThrown){
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });
            request.always(function () {
                request = undefined;
            });
        }
        $('#searchQuery').on('keyup', function(){
            if(prevSearch == $(this).val()){
                return;
            }
            prevSearch == $(this).val();
            $('#table_container tbody').html('');
            $('#table_container tbody').append('<tr class="text-center"><td colspan="4">Searching...</td></tr>');
            fetchFiles($(this).val());
            console.log("DATAQUERY",dataQuery);
        })
        $('#file-tree-con').on('click','.file-tree', function(){
            let index = parseInt($(this).parent().index());
            let i = index / 2;
            if(steps[i] !== undefined){
                $('#file-tree-con').children().slice(index + 2).detach();
                console.log(steps[i]);
                eval(steps[i])
                r_type = undefined;
                steps.splice(i + 1, steps.length - (i + 1) );
                query.splice(i, query.length - i);
                if(i == 0){
                    query = [];
                }
                $('#folder_container').show();
            }
            
            fetchFiles();
        })
        $('#download_files').on('click', function(){
            $(this).next('div').show();
            $(this).hide();
            let dataQuery = {
                where: query,
                type: r_type
            }
           console.log("dataQuery", dataQuery);
            if (downloadRequest) {
                request.abort();
            }
            downloadRequest = $.ajax({
                url: window.location.origin+'/download/files',
                type: "post",
                data: dataQuery,
            });
            downloadRequest.done(function(result){
                console.log("RESULT",result);
                let href = window.location.origin+'/download/archived_files/'+window.btoa(result);
                // let a = $('<a href="'+href+'" download></a>');
                // a.click();
                    var link=document.createElement('a');
                    link.href=href;
                    link.download='a';
                    link.click();
                    $('#download_files').next('div').hide();
                    $('#download_files').show();
            });
            downloadRequest.fail(function (jqXHR, textStatus, errorThrown){
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });
            downloadRequest.always(function () {
                downloadRequest = undefined;
            });
        })
        function show_files(){
            $('#table_container tbody').html('');
            let num_files = 0;
            let num = 1;
            for (const key in files) {
                num_files = num_files+files[key].length;
                if(files[key].length > 0){
                    files[key].forEach(file => {
                        let created_at = luxon.DateTime.fromISO(file.created_at).toFormat('ff');
                        let el = '<tr class="text-center">';
                        el += '<td>'+num+'</td>';
                        el+='<td class="text-center">';
                        let first = true;
                        let fl;
                        if(file.file_names){
                            fl = JSON.parse(file.file_names);
                        }
                        fl.forEach(element => {
                            if(first){
                                first = false;
                                el+='<a class="venobox" data-gall="'+file.file_path+'" data-vbtype="iframe" href="/media/'+file.file_path+'/'+element+'">Files ('+fl.length+')</a>';
                            }
                            else{
                                el+='<a class="venobox d-none" data-gall="'+file.file_path+'" data-vbtype="iframe" href="/media/'+file.file_path+'/'+element+'"></a>';
                            }
                        });
                        el+='</td>';
                        el += '<td class="text-capitalize">'+key+' Research</td>';
                        el += '<td class="text-capitalize">'+created_at+'</td>';
                        $('#table_container tbody').append(el);
                        num++;
                        $('.venobox').venobox({
                            framewidth : '80%',
                            frameheight: '90vh',
                            share      : ['download']
                        }); 
                    });
                    
                }

            }
            if(num_files == 0){
                $('#table_container tbody').append('<tr class="text-center text-danger"><td colspan="4">No file found</td></tr>');
            }
        }
    </script>
@endsection