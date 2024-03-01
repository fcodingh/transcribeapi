<?php
//Objective: Constructs a receiving end of post messages/files for testing uploads of files

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//namespaces added for functionality
use Illuminate\Support\Facades\Storage; //required to access the filesystems-local or remote, private or public 


class TestingToolPostController extends Controller
{
       public function readPost(Request $request){  
        try{
            // Validate incoming request data
            $validatedData = $request->validate([
                'field1' => 'required',
                'field2' => 'required',
                'file' => 'required|file', // Validation rule for the file upload
                // Add more validation rules as needed
            ]);

            // Accessing individual fields from the validated data
            $field1Value = $validatedData['field1'];
            $field2Value = $validatedData['field2'];
            
            // Process the file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $writtenToPath= $file->storeAs($file->hashName(),$file->getClientOriginalName()); //('local',$file->getClientOriginalName())- another option is to speficy the folder in the path as primary parameter in addtiona to the name 
                //workingFine $writeTo= Storage::disk('local')->put($file->getClientOriginalName(),$file->get()); // Move the uploaded file to a directory
                //$filePath = $file->store($path); // Adjust the path as needed
                // You can also get more information about the file like size, extension, etc.
                $fileSize = $file->getSize();
                $fileExtension = $file->getClientOriginalExtension();
                // Process other file-related tasks as needed
            }

            // Process the request
            // Perform actions based on the received data
            
            // Return a response
            return response()->json(['message' => 'Request processed successfully', 'data' => $validatedData,$writtenToPath], 200);
        }
        catch(\Exception $e){
            return response()->json(["error"=> $e->getMessage()],400);
        }
        
    }//end of the readPost function

        
       
}//end of the class
