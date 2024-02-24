<?php
//Objective: Constructs a 
namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//namespaces added for functionality
use Illuminate\Support\Facades\Storage; //required to access the filesystems-local or remote, private or public 


class TranscribeController extends Controller
{
    //class variables 
    protected $modelObjec1;
        
    public function __construct()
    {
        //$this->model = $modelObjec1;
    }
    
    public function mttext(Request $request,string $filename,string $disk){  
        try{
            //1- Pending: Test if user has token and is allows to make the call. This shall be a the router 
            
            //2- Verify if the file exist at any location: We will get the filename=Location, and look if it exist on the location provided
            $messageArray=[];
            if( Storage::disk($disk)->exists($filename)){
                $content=Storage::disk($disk)->get($filename);
                $messageArray=[ 
                    'message'=>'Transcribing file',
                    'Content' =>$content,
                    'fileUrl' => Storage::disk($disk)->url($filename),
                    'TransactionId#' => 'Pending',
                    ];
                //read file from the private folder 

            
            } else {
                //file not found 
                $messageArray='File does not exist';
            }

            return response()->json(['Message'  => $messageArray],201);

        }
        catch(\Exception $e){
            return response()->json(["error"=> $e->getMessage()],400);
        }
        
    }//end of public function transcribe- public

    private function pushToService(){

    }


}//end of the class
