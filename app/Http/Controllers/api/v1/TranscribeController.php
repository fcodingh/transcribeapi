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
        
    public function __construct(){
        //$this->model = $modelObjec1;
    }
    
    public function transcribe(Request $request,string $filename){  
        try{
            //1- Pending: Test if user has token and is allows to make the call. This shall be a the router 
            
            //2- Verify if the file exist at any location: We will get the filename=Location, and look if it exist on the location provided
            if( $localStorageObject=Storage::disk("local")->exists($filename)){
                $localStorageObject = Storage::disk("local");


            }            
            
            return response()->json([
                'diskList'=> config('filesystems.disks'),
                'filename' =>$filename,
                'fileExist'=> $localStorageObject,
                'fileSystem'=> $localStorageObject,
                'request'  => $request->cookie(),
                'status'   => 'successing',
                'Message'  => 'Dale pal carajo con eso',
            ],201);
        }
        catch(\Exception $e){
            return response()->json(["error"=> $e->getMessage()],400);
        }
    }











}//end of the class
