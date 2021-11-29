<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\PublishedResearch;
use App\Models\PresentedResearch;
use App\Models\CompletedResearch;
use App\Models\OngoingResearch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class UploadResearchController extends Controller
{

    public function show_upload_research()
    {
        $data = [
            "units"=>Unit::all(),
        ];

        return view('contents.upload_research', $data);
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
        $pr->status = 4;
        $pr->date_publication=request('date_publication');
        $pr->save();

        $filename = [];
        $dir = Auth::user()->id.'/published/'.$pr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $path = $file->store($dir);
                array_push($filename, $path);
            }
            PublishedResearch::where('id', $pr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return $pr;
        }
        else{
            return 'Path Exists';
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
        $pr->status = 4;
        $pr->save();

        $filename = [];
        $dir = Auth::user()->id.'/presented/'.$pr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $path = $file->store($dir);
                array_push($filename, $path);
            }
            PresentedResearch::where('id', $pr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return $pr;
        }
        else{
            return 'Path Exists';
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
        $cr->status = 4;
        $cr->save();

        $filename = [];
        $dir = Auth::user()->id.'/completed/'.$cr->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $path = $file->store($dir);
                array_push($filename, $path);
            }
            CompletedResearch::where('id', $cr->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return $cr;
        }
        else{
            return 'Path Exists';
        }
    }
    public function save_on_going(Request $request)
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
        $or->status = 4;
        $or->save();

        $filename = [];
        $dir = Auth::user()->id.'/ongoing/'.$or->id;
        if(!Storage::exists($dir)) {
            Storage::makeDirectory($dir);
            foreach($request->file('file') as $file){
                $path = $file->store($dir);
                array_push($filename, $path);
            }
            OngoingResearch::where('id', $or->id)->update(["file_path"=>$dir, "file_names"=>json_encode($filename)]);
            return $or;
        }
        else{
            return 'Path Exists';
        }
    }

    public function preview_file(Request $request, $filename)
    {
        $folder_name = '1/docs/5';
        $path = $folder_name.'/'.$filename;
        error_log($path);
        if(!Storage::exists($path)){
            abort(404);
        }

        return Storage::response($path);
    }
    public function show_published_research()
    {
        $data = PublishedResearch::where('owner_id', Auth::user()->id)->whereNotIn('status', $this->returnedStatus)->get();
        return view('contents.published_research', ["data"=>$data, "owner"=>"true"]);
    }
    public function show_completed_research()
    {
        $data = CompletedResearch::where('owner_id', Auth::user()->id)->get();
        return view('contents.completed_research', ["data"=>$data, "owner"=>"true"]);
    }
    public function show_presented_research()
    {
        $data = PresentedResearch::where('owner_id', Auth::user()->id)->get();
        return view('contents.presented_research', ["data"=>$data, "owner"=>"true"]);
    }
    public function show_ongoing_research()
    {
        $data = OngoingResearch::where('owner_id', Auth::user()->id)->get();
        return view('contents.ongoing_research', ["data"=>$data, "owner"=>"true"]);
    }
    public function show_fpes_research()
    {
        $ep = EvaluationPeriod::select('from', 'to')->orderBy('created_at', 'desc')->first();
        $data = FpesResearch::with('involvement:id,involvement,points')->with('evaluationPeriod:id,from,to')->where('owner_id', Auth::user()->id)->whereNotIn('status', $this->returnedStatus)->get();
        return view('contents.fpes_research', ["data"=>$data, "owner"=>"true", "ep"=>$ep]);
    }
}
