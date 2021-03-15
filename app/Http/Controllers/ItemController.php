<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function add(Request $request)
    {
        $item = Item::forceCreate(['name'=>$request->name]);
        return $item;
    }
    public function get(Request $request)
    {
        if ($request->type === 'all') {
            $item = Item::orderBy('created_at', 'desc')->get();
        } elseif ($request->type === 'completed') {
            $item = Item::where('is_complete', true)->orderBy('created_at', 'desc')->get();
        } else {
            $item = Item::where('is_complete', false)->orderBy('created_at', 'desc')->get();
        }

        $active = Item::where('is_complete', false)->count();
        $hasCompleted = Item::where('is_complete', true)->count();
        $total = Item::count();
        $ret = [
            'active_now' => $active,
            'has_completed' => $hasCompleted,
            'item' => $item,
            'total' => $total
        ];
        return response()->json($ret);
    }

    public function itemAction(Request $request) {
        if ($request->type === 'complete') {
            Item::where('id', $request->id)
                ->update(['is_complete' => true]);
        } else {
            Item::where('id', $request->id)
                ->update(['is_complete' => false]);
        }
    }

    public function update(Request $request)
    {
        Item::where('id', $request->id)
            ->update(['name'=>$request->name]);
    }

    public function delete(Request $request)
    {
        if ($request->type === 'single') {
            Item::where('id', $request->id)->delete();
        } else {
            Item::where('is_complete', true)->delete();
        }
    }
}
