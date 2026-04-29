<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('company.{company_id}.bot.{bot_id}', function ($user, $company_id, $bot_id) {
    // 1. Check if user belongs to the company
    if ((int) $user->company_id !== (int) $company_id && !$user->isSuperAdmin()) {
        return false;
    }

    // 2. Check if the bot belongs to that company
    $bot = \Modules\Company\Models\TelegramBot::find($bot_id);
    if (!$bot || (int) $bot->company_id !== (int) $company_id) {
        return false;
    }

    // Return user data for presence channels if needed, otherwise true
    return [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->role,
    ];
});
