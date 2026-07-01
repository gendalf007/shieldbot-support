<?php

namespace App\Http\Controllers;

use App\Ai\Agents\HelpChatAgent;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class HelpChatController extends Controller
{
    /**
     * Stream a grounded answer (SSE) for the user's question.
     */
    public function stream(Request $request): Responsable
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|string|in:user,assistant',
            'history.*.content' => 'required_with:history|string',
        ]);

        // Keep only the last HISTORY_LIMIT messages as a server-side safety net.
        $history = array_slice(
            $validated['history'] ?? [],
            -HelpChatAgent::HISTORY_LIMIT
        );

        return (new HelpChatAgent($history, app()->getLocale()))->stream($validated['message']);
    }
}
