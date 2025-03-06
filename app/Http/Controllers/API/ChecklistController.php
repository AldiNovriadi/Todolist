<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\NoteList;
use Illuminate\Http\Request;
use App\Models\NoteListDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChecklistController extends Controller
{
    public function indexChecklist(Request $request){
        $data = NoteList::with('details')->where('user_id', Auth::user()->id);

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function createChecklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{

            $data = NoteList::create([
                'name' => $request->name,
                'user_id' => Auth::user()->id,
            ]);

        }catch (Exception $e){

            DB::rollback();
            return response()->json(['warning' => 'Error : '.$e->getMessage()], 500);
        }

        DB::commit();

        return response()->json([
            'data' => $data,
            'message' => 'Create Success'
        ], 200);
    }

    public function deleteChecklist($id){
        
        DB::beginTransaction();
        try{
            $data = NoteList::find($id);

            if (!$data) {
                return response()->json(['message' => 'Tidak ada data'], 404);
            }

            $data->delete();
        }catch (Exception $e){

            DB::rollback();
            return response()->json(['warning' => 'Error : '.$e->getMessage()], 500);
        }

        DB::commit();

        return response()->json([
            'message' => 'Delete Success'
        ], 200);
    }

    public function detailChecklist(Request $request, $id){
        $data = NoteList::with(['details', 'details.items'])->where('id', $id);

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function createChecklistItem(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'itemName' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{

            $dataChecklist = NoteList::find($id);

            if (!$dataChecklist) {
                return response()->json(['message' => 'Tidak ada data'], 404);
            }

            $data = NoteListDetail::create([
                'note_list_id' => $dataChecklist->id,
                'name' => $request->itemName,
            ]);

        }catch (Exception $e){

            DB::rollback();
            return response()->json(['warning' => 'Error : '.$e->getMessage()], 500);
        }

        DB::commit();

        return response()->json([
            'data' => $data,
            'message' => 'Create Success'
        ], 200);
    }

    public function detailChecklistItem(Request $request, $id, $idItem){
        $data = NoteList::with(['details' => function($query) use ($idItem) {
            $query->where('id', $idItem);
        }])
        ->where('id', $id)
        ->whereHas('details', function ($query) use ($idItem) {
            $query->where('id', $idItem);
        });

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function deleteChecklistItem(Request $request, $id, $idItem){

        $data = NoteListDetail::where('note_list_id', $id)->where('id', $idItem)->delete();

        return response()->json([
            'message' => 'Delete Success'
        ], 200);
    }

    public function updateChecklistItem(Request $request, $id, $idItem)
    {
        $validator = Validator::make($request->all(), [
            'itemName' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = NoteListDetail::find($idItem);

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data'], 404);
        }

        $data->name = $request->itemName;
        $data->save();

        return response()->json([
            'data' => $data,
            'message' => 'Update Success'
        ], 200);
    }

    public function updateStatusChecklistItem($id, $idItem)
    {
        $data = NoteListDetail::find($idItem);

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data'], 404);
        }

        $data->status = !$data->status;
        $data->save();

        return response()->json([
            'data' => $data,
            'message' => 'Update Success'
        ], 200);
    }



}
