<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        
        <title>RDO |</title>
        <link rel="icon" type="image/png" href="{{asset('images/rdo_logo.ico')}}"/>
        <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
        <link href="{{asset('css/custom_styles.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/venobox@1.9.3/venobox/venobox.min.css"/>
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .modal label{
                margin: 0;
                color: #949494;
            }
            .files-uploaded > div  > div{
                background: #e6e6e6;
                padding: 1rem .4rem;
                border-radius: .5rem;
                position: relative;
            }
            .files-uploaded > div  > div .fa-times{
                position: absolute;
                right: .5rem;
                top: .5rem;
                font-size: 75%;
                cursor: pointer;
            }
            .files-uploaded > div  > div .fa-times:hover{
                color: red;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        @php
            $units = $units = \App\Models\Unit::all();
        @endphp
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="../dist/"><img src="{{asset('images/rdo_logo.ico')}}" width="25%" height="30%"> <span style="font-size:150%;">RDO</span></a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button
            ><!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav iml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i> {{Auth::user()->name}} </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="/dashboard">
								<div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Home
							</a>
                            <div class="sb-sidenav-menu-heading"><i>{{\App\Models\UserType::where('id', Auth::user()->user_type_id)->first()->user_type}}</i></div>
                            @if (Auth::user()->user_type_id === 2)
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                    Research
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav"> 
                                        <a class="nav-link" href="/upload"><i class="fas fa-table"></i>&nbsp;Upload Research</a>
                                        <a class="nav-link" href="/published"><i class="fas fa-list"></i>&nbsp;Published Research</a>
                                        <a class="nav-link" href="/completed"><i class="fas fa-list"></i>&nbsp;Completed Research</a>
                                        <a class="nav-link" href="/presented"><i class="fas fa-list"></i>&nbsp;Presented Research</a>
                                        <a class="nav-link" href="/ongoing"><i class="fas fa-list"></i>&nbsp;On-Going Research</a>
                                        <a class="nav-link" href="/fpes"><i class="fas fa-list"></i>&nbsp;FRI</a>
                                    </nav>
                                </div>
                            @endif
                            @if (Auth::user()->user_type_id == 2)
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#returnedPapersCollapse" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                    Returned Papers
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="returnedPapersCollapse" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav"> 
                                        <a class="nav-link" href="/returned/published"><i class="fas fa-list"></i>&nbsp;Published Research</a>
                                        <a class="nav-link" href="/returned/completed"><i class="fas fa-list"></i>&nbsp;Completed Research</a>
                                        <a class="nav-link" href="/returned/presented"><i class="fas fa-list"></i>&nbsp;Presented Research</a>
                                        <a class="nav-link" href="/returned/ongoing"><i class="fas fa-list"></i>&nbsp;On-Going Research</a>
                                        <a class="nav-link" href="/returned/fpes"><i class="fas fa-list"></i>&nbsp;FRI</a>
                                    </nav>
                                </div>
                            @endif
                            @if (Auth::user()->user_type_id == 3 || Auth::user()->user_type_id == 4)
                                <a class="nav-link" href="#"><i class="fas fa-list"></i>&nbsp;FRI</a>
                                <a class="nav-link" href="/pending/fpes"><i class="fas fa-list"></i>&nbsp;Pending FRI</a>
                                <a class="nav-link" href="/returned/fpes"><i class="fas fa-list"></i>&nbsp;Returned FRI</a>
                            @endif
                            @if (Auth::user()->user_type_id == 5)
                                <a class="nav-link" href="/files"><div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>&nbsp;Files</a>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                        Pending Research
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav"> 
                                        <a class="nav-link" href="/pending/published"><i class="fas fa-list"></i>&nbsp;Published Research</a>
                                        <a class="nav-link" href="/pending/completed"><i class="fas fa-list"></i>&nbsp;Completed Research</a>
                                        <a class="nav-link" href="/pending/presented"><i class="fas fa-list"></i>&nbsp;Presented Research</a>
                                        <a class="nav-link" href="/pending/ongoing"><i class="fas fa-list"></i>&nbsp;On-Going Research</a>
                                        <a class="nav-link" href="/pending/fpes"><i class="fas fa-list"></i>&nbsp;FRI</a>
                                    </nav>
                                </div>
                            @endif
                            @if (Auth::user()->user_type_id == 1)
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    Accounts
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="/accounts/pending">Pending Accounts</a>
                                        <a class="nav-link" href="/accounts/confirmed">Existing Accounts</a>
                                        <a class="nav-link" href="/accounts/disabled">Disabled Accounts</a>
                                    </nav>
                                </div>
                                <a class="nav-link" href="/rdo-registration"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>&nbsp;Add RDO</a>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                    Settings
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModal1" aria-expanded="false" aria-controls="pagesCollapseAuth">Add Unit</a>
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModal3" aria-expanded="false" aria-controls="pagesCollapseAuth">Set Evaluation Period</a>
                                            <a class="nav-link" href="#" data-toggle="modal" data-target="#myModal2" aria-expanded="false" aria-controls="pagesCollapseAuth">Add Research Involvment</a>
                                            <a class="nav-link" href="report.php?"aria-expanded="false" aria-controls="pagesCollapseAuth">Report</a>
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <i>{{\App\Models\UserType::where('id', Auth::user()->user_type_id)->first()->user_type}}</i>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                @yield('content')
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; RDO </div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <div class="modal fade" id="confirmation_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="confirm_message"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn_neg" class="btn" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_pos" class="btn">Understood</button>
                </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="published_research_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="/update/published" id="update_published_form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Published Research</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label><small>Author</small></label>
                                <input type="text" name="author" class="form-control" placeholder="Author" required>
                            </div>
                            <div class="form-group">
                                <label><small>Publication Title</small></label>
                                <input class="form-control" name="publication_title" placeholder="Publication Title" title="Publication Title" required>
                            </div>
                            <div class="form-group">
                                <label><small>Journal Title</small></label>
                                <input class="form-control" name="journal_title" placeholder="Journal Title" title="Journal Title" required>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-3 pl-lg-0 p-0">
                                    <label><small>Vol.No./Issue</small></label>
                                    <input class="form-control" name="vol_no" placeholder="Vol.No./Issue" title="Vol.no./Issue" required>
                                </div>
                                <div class="form-group col-md-3 px-lg-2 p-0">
                                    <label><small>ISSN</small></label>
                                    <input class="form-control" name="issn" placeholder="ISSN" title="ISSN" required>
                                </div>
                                <div class="form-group col-md-4 px-lg-2 p-0">
                                    <label><small>Site</small></label>
                                    <input class="form-control" name="site" placeholder="Site" title="Site" required>
                                </div>
                                <div class="form-group col-md-2 pr-lg-0 p-0">
                                    <label><small>Pages</small></label>
                                    <input class="form-control" name="pages" placeholder="Pages" required>
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-4 pl-lg-0 p-0">
                                    <label><small>Indexing</small></label>
                                    <input class="form-control" name="indexing" placeholder="Indexing" required>
                                </div>
                                <div class="form-group col-md-4 px-lg-2 p-0">
                                    <label><small>Keyword</small></label>
                                    <input class="form-control" name="keyword" placeholder="Keyword" required>
                                </div>
                                <div class="form-group col-md-4 pr-lg-0 p-0">
                                    <label><small>School Year</small></label>
                                    <input class="form-control" name="school_year" placeholder="School Year" required>
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>Semester</small></label>
                                    <select class="form-control" name="semester" placeholder="Semester" required>
                                        <option value="" selected hidden>Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Unit</small></label>
                                    <select class="form-control" name="unit_id" placeholder="College/Unit" required>
                                        <option selected value="" hidden>Select College/Unit</option>
                                        <option value="">None</option>
                                        @foreach ($units as $unit)
                                            <option value="{{$unit->id}}">{{$unit->unit}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <div class="form-group">
                                        <label><small>Date Published</small></label>
                                        <input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Files</small></label>
                                    <div class="input-group mb-3 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple>
                                            <label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="published-files-uploaded" class="row m-0 files-uploaded">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_neg" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn_pos" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="completed_research_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="update_completed_form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Completed Research</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
							<div class="form-group">
                                <label><small>Author</small></label>
                                <input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
                            </div>
                            <div class="form-group">
                                <label><small>Title of Paper</small></label>
                                <input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>Keyword</small></label>
                                    <input class="form-control" name="keyword" placeholder="Keyword" required>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Semester</small></label>
                                    <select class="form-control" name="semester" placeholder="Semester" required>
                                        <option value="" selected="" hidden="">Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <div class="form-group">
                                        <label><small>Date Published</small></label>
                                        <input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Files</small></label>
                                    <div class="input-group mb-3 p-0">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple>
                                            <label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="completed-files-uploaded" class="row m-0 files-uploaded">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_neg" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn_pos" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="presented_research_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="update_presented_form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Presented Research</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
							<div class="form-group">
                                <label><small>Author</small></label>
                                <input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
                            </div>
                            <div class="form-group">
                                <label><small>Title of Paper</small></label>
                                <input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
                            </div>
                            <div class="form-group">
                                <label><small>Title, Venue and Date of Conference Where the Research Output Was Presented:</small></label>
                                <input class="form-control" name="title" placeholder="Title" title="Title" required>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>Venue</small></label>
                                    <input class="form-control" name="venue" placeholder="Venue" required="">
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Organizer</small></label>
                                    <input class="form-control" name="organizer" placeholder="Organizer" required="">
                                </div>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>Locale</small></label>
                                    <select class="form-control" name="locale" required="">
                                        <option value="" hidden="">Intl. or Local</option>
                                        <option value="intl">International</option>
                                        <option value="local">Local</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <div class="form-group">
                                        <label><small>Date Published</small></label>
                                        <input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Files</small></label>
                                <div class="input-group mb-3 p-0">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple>
                                        <label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div id="presented-files-uploaded" class="row m-0 files-uploaded">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_neg" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn_pos" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="_research_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="update__form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">On Going Research</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
							<div class="form-group">
                                <label><small>Author</small></label>
                                <input class="form-control" name="author" type="text" id="author" placeholder="Author" required>
                            </div>
                            <div class="form-group">
                                <label><small>Title of Paper</small></label>
                                <input class="form-control" name="paper_title" placeholder="Title of Paper" title="Title of Paper" required>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>Keyword</small></label>
                                    <input class="form-control" name="keyword" placeholder="Keyword" required>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Semester</small></label>
                                    <select class="form-control" name="semester" placeholder="Semester" required>
                                        <option value="" selected="" hidden="">Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row m-0">
                                <div class="form-group mb-0 col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <div class="form-group">
                                        <label><small>Date Published</small></label>
                                        <input class="form-control d-inline-block" name="date_publication" id="date_publication" type="date" placeholder="Date of Publication" required>
                                    </div>
                                </div>
                                <div class="form-group mb-0 col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>Research Deliverables</small></label>
                                    <select class="form-control" name="research_deliverable" required="">
                                        <option value="" selected="" hidden="">Research/Deliverables</option>
                                        <option value="Concept Paper">Concept Paper</option>
                                        <option value="Proposal">Proposal</option>
                                        <option value="Full Paper">Full Paper</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><small>Files</small></label>
                                <div class="input-group mb-3 p-0">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file[]" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" multiple>
                                        <label class="custom-file-label text-truncate" for="inputGroupFile01">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div id="-files-uploaded" class="row m-0 files-uploaded">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_neg" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn_pos" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fpes_research_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="update_fpes_form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">FRI Research</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

							<div class="form-group">
                                <label><small>Involvement</small></label>
                                <select class="form-control" name="involvement_id" required>
                                    @foreach (\App\Models\Involvement::all() as $inv)
                                        <option value="{{$inv->id}}">{{$inv->involvement}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label><small>Title of Paper</small></label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="row m-0">
                                <div class="form-group col-md-6 pl-lg-0 pr-lg-1 p-0">
                                    <label><small>From:</small></label>
                                    <input class="form-control" type="date" name="from" required>
                                </div>
                                <div class="form-group col-md-6 pr-lg-0 pl-lg-1 p-0">
                                    <label><small>To:</small></label>
                                    <input class="form-control" type="date" name="to" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_neg" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn_pos" class="btn btn-info">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="for_verify_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select name="verify_action" class="form-control" id="verify_action">
                        <option value="1">Accept</option>
                        <option value="2">Return</option>
                    </select>
                    <div class="mt-3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="verify_btn" class="btn btn-success">Confirm</button>
                </div>
                </div>
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        {{-- <script src="js/scripts.js"></script> --}}
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script> --}}
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        {{-- <script src="assets/demo/datatables-demo.js"></script> --}}
        <script src="https://unpkg.com/venobox@1.9.3/venobox/venobox.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/2.0.2/luxon.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function(){
                $('.venobox').venobox({
                    framewidth : '80%',
                    frameheight: '90vh',
                    share      : ['download']
                }); 
            });
            function ConfirmationModal(id, obj){
                let opts = obj;
                var request;
                $('#confirm_message').html(opts.message || 'Please click '+opts.btn+'to proceed.');
                $('#btn_pos').html(opts.btn || 'Confirm');
                $("#btn_pos").attr('class', 'btn btn-'+(opts.btnClass || 'primary'));
                $('#btn_neg').attr('class', 'btn btn-outline-'+(opts.btnClass || 'primary'));
                $(id).modal('show');
                $('#btn_pos').off('click');
                $('#btn_pos').on('click', function(){
                    opts.data['_token'] = '{{ csrf_token() }}';
                    console.log(opts.data);
                    if (request) {
                        toastr.info('Please wait...');
                        request.abort();
                    }
                    request = $.ajax({
                        url: window.location.origin+opts.url,
                        type: "post",
                        data: opts.data
                    });
                    request.done(opts.done);
                    request.fail(function (jqXHR, textStatus, errorThrown){
                        toastr.error('Something went wrong');
                        console.error(
                            "The following error occurred: "+
                            textStatus, errorThrown
                        );
                    });
                    request.always(function () {
                        $(id).modal('hide');
                    });
                });
                
            }
        </script>
        <script src="{{asset('/js/main.js')}}"></script>
        @yield('script')
    </body>
</html>
