<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Response;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VirtualCardLogController extends Controller
{
    /**
     * Method for virtual card logs
     */
    public function index()
    {
        $page_title = 'Virtual Card Logs';
        $transactions = Transaction::virtualCard()->orderBy('id', 'desc')->get();

        return view('admin.sections.virtual-card-logs.index', compact(
            'page_title',
            'transactions'
        ));
    }

    /**
     * Method for virtual card log details page
     */
    public function details($id)
    {
        $page_title = 'Virtual Card Log Details';
        $transaction = Transaction::virtualCard()->with(['user', 'user_wallet'])->where('id', $id)->first();

        if (! $transaction) {
            return back()->with(['error' => ['Data not found']]);
        }

        return view('admin.sections.virtual-card-logs.details', compact(
            'page_title',
            'transaction'
        ));
    }

    /**
     * Method for search buy crypto log
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);
        if ($validator->fails()) {
            $error = ['error' => $validator->errors()];

            return Response::error($error, null, 400);
        }

        $validated = $validator->validate();

        $transactions = Transaction::auth()->virtualCard()
            ->search($validated['text'])->get();

        return view('admin.components.search.virtual-card-log-search', compact('transactions'));
    }
}
