<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $notes = Note::where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
            return response()->json([
                'data' => $notes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'note' => 'required',
            ]);

            $note = new Note();
            $note->user_id = $request->user()->id;
            $note->title = $request->title;
            $note->note = $request->note;
            $note->save();

            return response()->json([
                'status' => 'Catatan berhasil disimpan!',
                'data' => $note
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $note = Note::find($id);
            return response()->json([
                'data' => $note
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'note' => 'required',
            ]);

            $note = Note::find($id);
            $note->title = $request->title;
            $note->note = $request->note;
            $note->save();

            return response()->json([
                'status' => 'Catatan berhasil diupdate!',
                'data' => $note
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $note = Note::find($id);
            $note->delete();
            return response()->json([
                'status' => 'Catatan berhasil dihapus!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
