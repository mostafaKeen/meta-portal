<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Models\TelegramBot;
use Modules\Company\Http\Requests\StoreTelegramBotRequest;

class TelegramBotController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        $bots = $company->telegramBots()->latest()->get();

        return view('company::telegram.index', compact('bots'));
    }

    public function create()
    {
        return view('company::telegram.create');
    }

    public function store(StoreTelegramBotRequest $request)
    {
        $company = auth()->user()->company;

        if ($company->hasReachedTelegramLimit()) {
            return redirect()->back()
                ->with('error', 'You have reached the maximum number of Telegram bots allowed for your plan.')
                ->withInput();
        }

        $data = $request->validated();
        $data['company_id'] = $company->id;

        TelegramBot::create($data);

        return redirect()->route('company.telegram.index')
            ->with('success', 'Telegram bot added successfully.');
    }

    public function edit(TelegramBot $telegram)
    {
        if ($telegram->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('company::telegram.edit', compact('telegram'));
    }

    public function update(Request $request, TelegramBot $telegram)
    {
        if ($telegram->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'token'  => ['required', 'string', 'unique:telegram_bots,token,' . $telegram->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $telegram->update($data);

        return redirect()->route('company.telegram.index')
            ->with('success', 'Telegram bot updated successfully.');
    }

    public function destroy(TelegramBot $telegram)
    {
        if ($telegram->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $telegram->delete();

        return redirect()->route('company.telegram.index')
            ->with('success', 'Telegram bot deleted successfully.');
    }
}
