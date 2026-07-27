<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Complaint\Actions\CreatePublicComplaintAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function create(): View
    {
        return view('frontend.complaints.create');
    }

    public function store(Request $request, CreatePublicComplaintAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'website' => ['nullable', 'max:0'],
            'name' => ['required', 'string', 'max:120'],
            'contact' => ['required', 'string', 'max:190'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'message.min' => 'Isi keluhan minimal 20 karakter.',
        ]);

        $complaint = $action->execute([
            ...$validated,
            'source_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('complaints.create')->with([
            'complaint_submitted' => true,
            'complaint_reference' => $complaint->reference_code,
        ]);
    }
}
