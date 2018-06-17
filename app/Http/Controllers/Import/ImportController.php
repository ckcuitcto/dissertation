<?php

namespace App\Http\Controllers\Import;

use App\Model\FileImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index(){
        $imports = FileImport::all();
        return view('import.index',compact('imports'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $file = FileImport::find($id);
        if (!empty($file)) {
//            unlink()
            $file->delete();
            //sau khi xóa học kì thì cũng xóa form đánh giá
            return response()->json([
                'file' => $file,
                'status' => true
            ], 200);
        }
        return response()->json([
            'status' => false
        ], 200);
    }
}
