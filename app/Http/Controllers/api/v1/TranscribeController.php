<?php
//Objective: Constructs a 
namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//namespaces added for functionality
use GuzzleHttp\Client; // needed to create an http post request 
use Illuminate\Support\Facades\Storage; //required to access the filesystems-local or remote, private or public 
use Illuminate\Support\Facades\Http; //required to push a request up to the API 


class TranscribeController extends Controller
{
    //class variables 
    protected $modelObjec1;
        
    public function __construct()
    {
        //$this->model = $modelObjec1;
    }
    
    public function mttext(Request $request){   /*,string $filename,string $disk*/
        try{
            //1- Pending: Test if user has token and is allows to make the call. This shall be a the router 
            
            //2- Verify if the file exist at any location: We will get the filename=Location, and look if it exist on the location provided
            //reading the session arguments/variables 
            $validatedData = $request->validate([
                'disk' => 'required',
                'path' => 'required',
                'filename' => 'required', // Validation rule for the file upload
                // Add more validation rules as needed
            ]);
            // Accessing individual fields from the validated data
            $disk = $validatedData['disk'];
            $path = $validatedData['path'] .'\\'. $validatedData['filename'];
                       
            if( Storage::disk($disk)->exists($path)){ //this solution is specific to the Laravel Storage folder. Pending: WE may need something more general 
                //read file from the private folder and push to the transcription service
                $getlocalDir = storage_path() . '\\app\\' . $path;
                //$content = fopen($getlocalDir,'rb'); 
                //$fileContentSize=filesize($getlocalDir) ;
                $content=Storage::disk($disk)->readStream($path);
                $fileContentSize=Storage::disk($disk)->size($path);
                $filetype=Storage::disk($disk)->mimeType($path);
                $filepath=Storage::disk($disk)->path($path);
                
                $results= $this->pushToService($content,$fileContentSize); 
            } else {
                //file not found 
                //$messageArray='File does not exist';
            }
            //return response()->json(['Message'  => $results],201);
            return response()->json($results,201);

        }catch(\Exception $e){
            return response()->json(["error"=> $e->getMessage()],400);
        }
        
    }//end of public function transcribe- public

    private function pushToService($content,$fileContentSize){
        //making the call to the transcribing service
        try{
            //making a call to the transcription service
            $curl = curl_init(); //preparing a curl object 
            curl_setopt($curl, CURLOPT_URL, 'https://api.deepgram.com/v1/listen?diarize=true&language=en-US&model=nova-2&paragraphs=true&utterances=true');
            curl_setopt($curl, CURLOPT_POST, true); //set the request to POST
            //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Disabled it to get the response in the right Json format from API response. Otherwise, you get it in text. set the response to true. allowing us to receive the json returned from the service
            // Set the file handle as the request body
            curl_setopt($curl, CURLOPT_INFILE, $content); //pushing the file in stream format= binary, to the body of the request 
            //curl_setopt($curl, CURLOPT_INFILESIZE, $fileContentSize); // Setting the Content-Length of the body, which in this case is the file size
            // Set Authorization token/API key in the headers
            $headers = [
                'Authorization: Token ' . env('DeepGram_ACCCESS_KEY'), //416f5b22362560db498dbc12b19c12fffa657924', //key --> Pending --> Move the key to the evn ,and make the call
                'Content-Type: audio/mp4', // Adjust content type as per your requirement
                'Accept: application/json',    //*.*',
                'Connection: keep-alive',
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//pushing the header 
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
              //print_r(json_decode($response));
            }
            return $response;
        }catch(\Exception $e){
            return response()->json(["error"=> $e->getMessage()],400);
        }
    }//end PushToService function 


}//end of the class
