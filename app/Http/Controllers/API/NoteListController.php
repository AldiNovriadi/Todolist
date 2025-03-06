<?php

namespace App\Http\Controllers\API;

use DB;
use Exception;
use App\Models\NoteList;
use Illuminate\Http\Request;
use App\Models\NoteListDetail;
use App\Models\NoteListDetailItem;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteListController extends Controller
{
    public function indexNoteList(Request $request){
        $data = NoteList::with('details')->where('user_id', Auth::user()->id);

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function createNoteList(Request $request)
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

            if($request->has('details')){
                foreach($request->details as $index => $row){
                    $detail_data = [
                        "note_list_id" => $data->id,
                        "name" => $row['name'],
                        "status" => $row['status']
                    ];

                    $data_detail = NoteListDetail::create($detail_data);
                }
            }
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

    public function deleteNoteList($id){
        
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

    public function detailNoteList(Request $request, $id){
        $data = NoteList::with(['details', 'details.items'])->where('id', $id);

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function updateNoteList(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = NoteListDetail::find($id);

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data'], 404);
        }

        $data->name = $request->name;
        $data->save();

        return response()->json([
            'data' => $data,
            'message' => 'Update Success'
        ], 200);
    }

    public function updateStatusNoteList(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $data = NoteListDetail::find($id);

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data'], 404);
        }

        $data->status = $request->status;
        $data->save();

        return response()->json([
            'data' => $data,
            'message' => 'Update Success'
        ], 200);
    }

    // Detail to Items
    public function createNoteListDetailItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'detail_items' => 'required|array',
            'detail_items.*.name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        DB::beginTransaction();
        try{

            $data = NoteListDetail::find($id);

            if (!$data) {
                return response()->json(['message' => 'Tidak ada data'], 404);
            }

            if($request->has('detail_items')){
                foreach($request->detail_items as $index => $row){
                    $detail_data = [
                        "note_list_detail_id" => $data->id,
                        "name" => $row['name'],
                        "status" => $row['status']
                    ];

                    $data_detail = NoteListDetailItem::create($detail_data);
                }
            }
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

    public function detailNoteListDetailItem(Request $request, $id){
        $data = NoteListDetail::with('items')->where('id', $id);

        return response()->json([
            'data' => $data->get(),
            'message' => 'Success'
        ], 200);
    }

    public function deleteNoteListDetailItem($id){
        
        DB::beginTransaction();
        try{

            $data = NoteListDetail::find($id);

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
}
