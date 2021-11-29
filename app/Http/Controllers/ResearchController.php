<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\PublishedResearch;
use App\Models\PresentedResearch;
use App\Models\CompletedResearch;
use App\Models\OngoingResearch;
use App\Models\FpesResearch;
use App\Models\EvaluationPeriod;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use File;

// RESEARCH STATUS
// 3 - pending (at URC)
// 4 - pending (at CRC)
// 5 - pending (at RDO)

// 23 - returned from URC to Teacher
// 34 - returned from CRC to URC
// 45 - returned from RDO to CRC


class ResearchController extends Controller
{
    private $returnedStatus = [23];
    private $semiAdmintypes = [3, 4, 5];
    private $allowedType = ['published', 'presented', 'completed', 'ongoing', 'fpes'];
    public function show_upload_research()
    {
        $data = [
            "units"=>Unit::all(),
        ];
        return view('contents.upload_research', $data);
    }

    public function show_dashboard()
    {
        $_c = CompletedResearch::distinct()->pluck('date_publication')->toArray();
        
        // whereYear('created_at', '=', $year)
        //       ->whereMonth('created_at', '=', $month)
        //       ->get();
        return view('contents.dashboard');
    }

    public function show_pending_research($type)
    {
        if(!in_array($type, $this->allowedType) || !in_array(Auth::user()->user_type_id, $this->semiAdmintypes)){
            abort(404);
        }
        if(Auth::user()->user_type_id == 3){
            $ids = User::where([['unit_id', Auth::user()->unit_id],['user_type_id', 2]])->pluck('id')->toArray();
        }
        else if(Auth::user()->user_type_id == 4){
            $ids = User::where([['college_id', Auth::user()->college_id],['user_type_id', 2]])->pluck('id')->toArray();
        }
        else if(Auth::user()->user_type_id == 5){
            $ids = User::pluck('id')->toArray();
        }
        $research = [];
        $ep = null;
        $owner = 'false';
        
        if($type == 'fpes'){
            $research = FpesResearch::with('involvement:id,involvement,points')->with('evaluationPeriod:id,from,to')->where('status', Auth::user()->user_type_id)->whereIn('owner_id', $ids)->get();
            $ep = EvaluationPeriod::select('from', 'to')->orderBy('created_at', 'desc')->first();
        }
        else{
            $research = DB::table($type.'_research')->where('status', Auth::user()->user_type_id)->whereIn('owner_id', $ids)->get();
        }

        $data = [
            "data"=>$research, 
            "owner"=>$owner, 
            "view"=>"pending-view",
            "ep"=>$ep,
        ];
        return view('contents.'.$type.'_research', $data);
    }
    
    public function show_main_folders()
    {






        // if(Auth::user()->user_type_id == 5){
        //     $ids = User::where([['unit_id', Auth::user()->unit_id],['user_type_id', 2]])->pluck('id')->toArray();
        // }
        // else if(Auth::user()->user_type_id == 6){
        //     $ids = User::where([['college_id', Auth::user()->college_id],['user_type_id', 2]])->pluck('id')->toArray();
        // }
        // else if(Auth::user()->user_type_id == 7){
        //     $ids = User::pluck('id')->toArray();
        // }


        return view('contents.files');
    }
    public function show_files()
    {
        return view('contents.files');
    }
    public function show_research($type)
    {
        if(!in_array($type, $this->allowedType) || in_array(Auth::user()->user_type_id, $this->semiAdmintypes)){
            abort(404);
        }
        
        $research = [];
        $ep = null;
        $owner = 'true';
        
        if($type == 'fpes'){
            $research = FpesResearch::with('involvement:id,involvement,points')->with('evaluationPeriod:id,from,to')->where('owner_id', Auth::user()->id)->whereNotIn('status', $this->returnedStatus)->get();
            $ep = EvaluationPeriod::select('from', 'to')->orderBy('created_at', 'desc')->first();
        }
        else{
            $research = DB::table($type.'_research')->where('owner_id', Auth::user()->id)->whereNotIn('status', $this->returnedStatus)->get();
        }

        $data = [
            "data"=>$research, 
            "owner"=>$owner, 
            "view"=>"pending-view",
            "ep"=>$ep,
        ];
        return view('contents.'.$type.'_research', $data);
    }
    public function show_returned_research($type)
    {
        if(!in_array($type, $this->allowedType)){
            abort(404);
        }

        $status = [
            2 => 23,
            3 => 34,
            4 => 45,
        ];

        $research = [];
        $ep = null;
        $owner = 'false';

        if(Auth::user()->user_type_id == 2){
            $owner = 'true';
        }
        
        if($type == 'fpes'){
            $research = FpesResearch::with('involvement:id,involvement,points')->with('evaluationPeriod:id,from,to')->where('status', $status[Auth::user()->user_type_id])->get();
            $ep = EvaluationPeriod::select('from', 'to')->orderBy('created_at', 'desc')->first();
        }
        else{
            $research = DB::table($type.'_research')->where('status', $status[Auth::user()->user_type_id])->get();
        }

        $data = [
            "data"=>$research, 
            "owner"=>$owner, 
            "view"=>"returned-view",
            "ep"=>$ep,
        ];
        return view('contents.'.$type.'_research', $data);
    }


    public function save_published(Request $request)
    {
        $pr = new PublishedResearch;
        $pr->author=request('author');
        $pr->publication_title=request('publ_title');
        $pr->journal_title=request('jrnl_title');
        $pr->vol_no=request('vol_no');
        $pr->issn=request('issn');
        $pr->site=request('site');
        $pr->pages=request('page');
        $pr->indexing=request('index');
        $pr->keyword=request('keyword');
        $pr->school_year=request('school_year');
        $pr->semester=request('sem');
        $pr->unit_id=request('college_unit');
        $pr->owner_id=Auth::user()->id;
        $pr->file_path="";
        $pr->file_names="";
        $pr->status = 5;
        $pr->date_publication=request('date_publication');
        $pr->save();

        $filename = [];
        $dir = Auth::user()->id.'/published/'.$pr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $name =  $file->getClientOriginalName();
                $path = $file->storeAs($dir, $name);
                array_push($filename, $name);
            }
            PublishedResearch::where('id', $pr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return redirect('/upload')->with(["data"=>"success"]);
        }
        else{
            return 'Something went wrong';
        }
    }
    public function save_presented(Request $request)
    {
        $pr = new PresentedResearch;
        $pr->author=request('author');
        $pr->paper_title=request('paper_title');
        $pr->title=request('title');
        $pr->venue=request('venue');
        $pr->organizer=request('organizer');
        $pr->locale=request('locale');
        $pr->owner_id=Auth::user()->id;
        $pr->date_publication=request('date_publication');
        $pr->file_path="";
        $pr->file_names="";
        $pr->status = 5;
        $pr->save();

        $filename = [];
        $dir = Auth::user()->id.'/presented/'.$pr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $name =  $file->getClientOriginalName();
                $path = $file->storeAs($dir, $name);
                array_push($filename, $name);
            }
            PresentedResearch::where('id', $pr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return redirect('/upload')->with(["data"=>"success"]);
        }
        else{
            return 'Something went wrong';
        }
    }
    public function save_completed(Request $request)
    {
        $cr = new CompletedResearch;
        $cr->author=request('author');
        $cr->paper_title=request('paper_title');
        $cr->keyword=request('keyword');
        $cr->semester=request('sem');
        $cr->owner_id=Auth::user()->id;
        $cr->date_publication=request('date_publication');
        $cr->file_path="";
        $cr->file_names="";
        $cr->status = 5;
        $cr->save();

        $filename = [];
        $dir = Auth::user()->id.'/completed/'.$cr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $name =  $file->getClientOriginalName();
                $path = $file->storeAs($dir, $name);
                array_push($filename, $name);
            }
            CompletedResearch::where('id', $cr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return redirect('/upload')->with(["data"=>"success"]);
        }
        else{
            return 'Something went wrong';
        }
    }
    public function save_ongoing(Request $request)
    {
        $or = new OngoingResearch;
        $or->author=request('author');
        $or->paper_title=request('paper_title');
        $or->keyword=request('keyword');
        $or->semester=request('sem');
        $or->research_deliverable=request('research_deliverable');
        $or->owner_id=Auth::user()->id;
        $or->date_publication=request('date_publication');
        $or->file_path="";
        $or->file_names="";
        $or->status = 5;
        $or->save();

        $filename = [];
        $dir = Auth::user()->id.'/ongoing/'.$or->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $name =  $file->getClientOriginalName();
                $path = $file->storeAs($dir, $name);
                array_push($filename, $name);
            }
            OngoingResearch::where('id', $or->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return redirect('/upload')->with(["data"=>"success"]);
        }
        else{
            return 'Something went wrong';
        }
    }
    public function save_fpes(Request $request)
    {
        $ep = EvaluationPeriod::select('id')->orderBy('created_at', 'desc')->first();
        $fpes_data = json_decode(request('fpes'));
        $fpes;
        foreach($fpes_data as $fd){
            $fpes = new FpesResearch;
            $fpes->involvement_id = $fd->involvement_id;
            $fpes->evaluation_period_id = $ep->id;
            $fpes->description = $fd->description;
            $fpes->from = $fd->from;
            $fpes->to = $fd->to;
            $fpes->status = 3;
            $fpes->owner_id = Auth::user()->id;
            $fpes->save();
        }
        return $fpes;
    }

    public function update_published(Request $request)
    {
        $file_names = json_decode(request('file_names'), true);
        $data=[
            "author"=>request('author'),
            "publication_title"=>request('publication_title'),
            "date_publication"=>request('date_publication'),
            "journal_title"=>request('journal_title'),
            "vol_no"=>request('vol_no'),
            "issn"=>request('issn'),
            "site"=>request('site'),
            "pages"=>request('pages'),
            "indexing"=>request('indexing'),
            "keyword"=>request('keyword'),
            "school_year"=>request('school_year'),
            "semester"=>request('semester'),
            "unit_id"=>request('unit_id'),
            "file_names"=>json_encode($file_names),
        ];

        PublishedResearch::where('id', request('id'))->update($data);
        return $this->update_files(request('file_path'), $file_names, $request->file('file'));
    }
    public function update_completed(Request $request)
    {
        $file_names = json_decode(request('file_names'), true);
        $data=[
            "author"=>request('author'),
            "paper_title"=>request('paper_title'),
            "date_publication"=>request('date_publication'),
            "keyword"=>request('keyword'),
            "semester"=>request('semester'),
            "file_names"=>json_encode($file_names),
        ];

        CompletedResearch::where('id', request('id'))->update($data);
        return $this->update_files(request('file_path'), $file_names, $request->file('file'));
    }
    public function update_presented(Request $request)
    {
        $file_names = json_decode(request('file_names'), true);
        $data=[
            "author"=>request('author'),
            "paper_title"=>request('paper_title'),
            "title"=>request('title'),
            "venue"=>request('venue'),
            "organizer"=>request('organizer'),
            "locale"=>request('locale'),
            "date_publication"=>request('date_publication'),
            "file_names"=>json_encode($file_names),
        ];

        PresentedResearch::where('id', request('id'))->update($data);
        return $this->update_files(request('file_path'), $file_names, $request->file('file'));
    }
    public function update_ongoing(Request $request)
    {
        $file_names = json_decode(request('file_names'), true);
        $data=[
            "author"=>request('author'),
            "paper_title"=>request('paper_title'),
            "date_publication"=>request('date_publication'),
            "keyword"=>request('keyword'),
            "semester"=>request('semester'),
            "research_deliverable"=>request('research_deliverable'),
            "file_names"=>json_encode($file_names),
        ];

        OngoingResearch::where('id', request('id'))->update($data);
        return $this->update_files(request('file_path'), $file_names, $request->file('file'));
    }
    public function update_fpes(Request $request)
    {
        $data=[
            "involvement_id"=>request('involvement_id'),
            "description"=>request('description'),
            "from"=>request('from'),
            "to"=>request('to'),
        ];
        FpesResearch::where('id', request('id'))->update($data);
        return 'success';
    }


    public function delete_published(Request $request)
    {
        $d = PublishedResearch::where([['id', request('id')], ['owner_id',Auth::user()->id]])->delete();
        if($d){
            Storage::deleteDirectory(request('file_path'));
        }
    }
    public function delete_completed(Request $request)
    {
        $d = CompletedResearch::where([['id', request('id')], ['owner_id',Auth::user()->id]])->delete();
        if($d){
            Storage::deleteDirectory(request('file_path'));
        }
    }
    public function delete_presented(Request $request)
    {
        $d = PresentedResearch::where([['id', request('id')], ['owner_id',Auth::user()->id]])->delete();
        if($d){
            Storage::deleteDirectory(request('file_path'));
        }
    }
    public function delete_ongoing(Request $request)
    {
        $d = OngoingResearch::where([['id', request('id')], ['owner_id',Auth::user()->id]])->delete();
        if($d){
            Storage::deleteDirectory(request('file_path'));
        }
    }
    public function delete_fpes(Request $request)
    {
        $d = FpesResearch::where([['id', request('id')], ['owner_id',Auth::user()->id]])->delete();
    }

    public function update_research_status($type)
    {
        if(!in_array($type, $this->allowedType)){
            abort(404);
        }
        $returned_to;
        $submit_to;
        if($type == "fpes"){
            $returned_to = [
                3=>23,
                4=>34,
                5=>45,
            ];
            $submit_to = [
                2=>3,
                3=>4,
                4=>5,
                5=>6,
            ];
        }
        else{
            $returned_to = [
                3=>23,
                4=>23,
                5=>23,
            ];
            $submit_to = [
                2=>5,
                3=>5,
                4=>5,
                5=>6,
            ];
        }
        // $accept = (Auth::user()->user_type_id == 2) ? 5:Auth::user()->user_type_id + 1;
        $id = request('id');
        $action = request('action');

        $data = [
            'updated_at'=>Carbon::now(),
        ];

        if($action == 2){
            $data['status'] = $returned_to[Auth::user()->user_type_id];
            $data['remarks']  = request('remarks');
        }
        else if($action == 1){
            $data['status'] = $submit_to[Auth::user()->user_type_id];
        }
        DB::table($type.'_research')->where('id', $id)->update($data);
        return $type;
    }


    
    public function update_files($folder_path, $file_names, $new_files)
    {
        if(@$new_files){
            foreach($new_files as $file){
                $name =  $file->getClientOriginalName();
                $path = $file->storeAs($folder_path, $name);
            }
        }
        $files = Storage::disk('local')->files($folder_path);

        $storage_file_names = [];

        foreach($files as $file){
            $r = explode("/",$file);
            $filename = end($r);
            
            if(!in_array($filename, $file_names)){
                // array_push($storage_file_names, $folder_path.'/'.$filename);
                Storage::delete($folder_path.'/'.$filename);
            }
        }
        return $storage_file_names;
    }


    public function preview_file($id, $type, $file_id, $filename)
    {
        $path = $id.'/'.$type.'/'.$file_id.'/'.$filename;
        error_log($path);
        if(!Storage::exists($path)){
            abort(404);
        }
        return Storage::response($path);
    }

    public function search_files()
    {
        $searchQuery = request('searchQuery');
        $conditions =  request('where') ?: [];
        $type = request('type');
        return $this->getFiles($conditions, $searchQuery, $type) ?: [];
    }
    public function getFiles($conditions, $searchQuery, $type)
    {
        $res = [];
        if($type){
            $users = $this->getUsers($conditions);
            if($type == 1){
                $res = ["published"=>$this->search_published_research($users, $searchQuery)];
            }
            else if($type == 2){
                $res = ["presented"=>$this->search_presented_research($users, $searchQuery)];
            }
            else if($type == 3){
                $res = ["completed"=>$this->search_completed_research($users, $searchQuery)];
            }
            else if($type == 4){
                $res = ["ongoing"=>$this->search_ongoing_research($users, $searchQuery)];
            }
            else if($type == 5){
                $res = ["fpes"=>$this->search_fpes_research($users, $searchQuery)];
            }
        }
        else{
            $users = $this->getUsers($conditions);  
            $res = [
                "published"=>$this->search_published_research($users, $searchQuery),
                "presented"=>$this->search_presented_research($users, $searchQuery),
                "completed"=>$this->search_completed_research($users, $searchQuery),
                "ongoing"=>$this->search_ongoing_research($users, $searchQuery),
                "fpes"=>$this->search_fpes_research($users, $searchQuery),
            ];
            
        }
        return $res;
    }

    public function search_published_research($users, $searchQuery)
    {
        if(count($users)>0){
            $res = PublishedResearch::whereIn('owner_id', $users)  
                ->with('users')	
                ->where('keyword','LIKE','%'.$searchQuery.'%')
                ->orWhere('journal_title','LIKE','%'.$searchQuery.'%')
                ->orWhere('publication_title','LIKE','%'.$searchQuery.'%')->get();
            return $res;
        }
        return [];
    }
    public function search_presented_research($users, $searchQuery)
    {
        if(count($users)>0){
            $res = PresentedResearch::whereIn('owner_id', $users) 
            ->with('users')				
            ->where('paper_title','LIKE','%'.$searchQuery.'%')
            ->orWhere('author','LIKE','%'.$searchQuery.'%')
            ->orWhere('title','LIKE','%'.$searchQuery.'%')->get();
            return $res;
        }
        return [];
    }
    public function search_completed_research($users, $searchQuery)
    {
        if(count($users)>0){
            $res = CompletedResearch::whereIn('owner_id', $users) 
                ->with('users')	
                ->where('paper_title','LIKE','%'.$searchQuery.'%')
                ->orWhere('author','LIKE','%'.$searchQuery.'%')
                ->orWhere('keyword','LIKE','%'.$searchQuery.'%')->get();
            return $res;
        }
        return [];
    }
    public function search_ongoing_research($users, $searchQuery)
    {
        if(count($users)>0){
            $res = OngoingResearch::whereIn('owner_id', $users) 
                ->with('users')				
                ->where('paper_title','LIKE','%'.$searchQuery.'%')
                ->orWhere('author','LIKE','%'.$searchQuery.'%')
                ->orWhere('keyword','LIKE','%'.$searchQuery.'%')->get();
            return $res;
        }
        return [];
    }
    public function search_fpes_research($users, $searchQuery)
    {
        if(count($users)>0){
                $res = FpesResearch::whereIn('owner_id', $users)
                ->with('users')				
                ->where('description','LIKE','%'.$searchQuery.'%')->get();
            return $res;
        }
        return [];
    }
    public function getUsers($conditions)
    {
        $users = [];
        if(count($conditions) > 0){
            $users = User::where($conditions)->pluck('id')->toArray();
        }
        else{
            $users = User::pluck('id')->toArray();
        }
        return $users;
    }
    public function makeZipWithFiles(string $zipPathAndName, array $filesAndPaths): void
    {
        $zip = new ZipArchive();
        $tempFile = tmpfile();
        $tempFileUri = stream_get_meta_data($tempFile)['uri'];
        if ($zip->open($tempFileUri, ZipArchive::CREATE) === TRUE) {
            // Add File in ZipArchive
            foreach($filesAndPaths as $file)
            {
                if (! $zip->addFile($file, basename($file))) {
                    echo 'Could not add file to ZIP: ' . $file;
                }
            }
            // Close ZipArchive
            $zip->close();
        } else {
            echo 'Could not open ZIP file.';
        }
        echo 'Path:' . $zipPathAndName;
        rename($tempFileUri, $zipPathAndName);
    }

    public function download_files()
    {
        // $data = $this->getFiles(request('where') ?: [], '', request('type'));
        $zip = new \ZipArchive();
        $fileName = 'researchFiles.zip';
        if ($zip->open(storage_path($fileName), \ZipArchive::CREATE)== TRUE)
        {
            $directories = Storage::directories('3/completed');
            foreach ($directories as $key => $value) {
                $path = 'app/'.$value;
                $files = File::files(storage_path($path));
                foreach ($files as $key => $value){
                    $relativeName = basename($value);
                    $zip->addFile($value, $relativeName);
                }
            }
            $zip->close();
        }

        // return response()->download(storage_path($fileName));
        return storage_path($fileName);
    }
    public function download_archived_files($path)
    {
        return response()->download(base64_decode($path));
    }

}
