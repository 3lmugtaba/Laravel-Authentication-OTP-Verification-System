<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramOtpService
{
    /** Send an OTP without ever exposing it in an HTTP response or log. */
    public function send(string $chatId, string $message): bool
    {
        $botToken = (string) config('services.telegram.bot_token');

        if ($botToken === '') {
            Log::error('Telegram OTP was not sent: TELEGRAM_BOT_TOKEN is not configured.');
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                ]);

            return $response->successful() && $response->json('ok') === true;
        } catch (\Throwable $exception) {
            Log::warning('Telegram OTP delivery failed.', [
                'exception' => $exception::class,
            ]);

            return false;
        }
    }
}
