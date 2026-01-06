<?php

namespace App\Services;

use App\Models\Market\Meme;
use App\Models\User;
use App\Models\Financial\Transaction;

class NotificationDispatcher
{
    /**
     * Create a new NotificationDispatcher instance.
     */
    public function __construct(private NotificationService $notificationService) {}

    /**
     * Send notification when a meme is approved.
     *
     * @param Meme $meme
     * @return void
     */
    public function memeApproved(Meme $meme): void
    {
        $this->notificationService->createNotification(
            $meme->creator,
            'Il tuo Meme è stato Approvato! 🚀',
            sprintf(
                'Il tuo meme "%s" ($%s) è stato approvato! Il trading inizierà a breve.',
                $meme->title,
                $meme->ticker
            )
        );
        $this->newMemeApprovedBroadcast($meme);
    }

    /**
     * Send notification when a meme is rejected.
     *
     * @param Meme $meme
     * @return void
     */
    public function memeRejected(Meme $meme): void
    {
        $this->notificationService->createNotification(
            $meme->creator,
            'Il tuo Meme è stato Rifiutato! 😕',
            sprintf(
                'Il tuo meme "%s" ($%s) è stato rifiutato perchè contiene contenuti inadeguati!',
                $meme->title,
                $meme->ticker
            )
        );
    }

    /**
     * Broadcast notification to all users when a new meme is approved and available for trading.
     *
     * @param Meme $meme
     * @return void
     */
    public function newMemeApprovedBroadcast(Meme $meme): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->notificationService->createNotification(
                $user,
                '🆕 Nuovo Meme Disponibile!',
                sprintf(
                    'Il meme "%s" ($%s) è ora disponibile per il trading!',
                    $meme->title,
                    $meme->ticker
                )
            );
        }
    }

    /**
     * Send notification when a dividend is received.
     *
     * @param User $user
     * @param float $amount
     * @param string $ticker
     * @return void
     */
    public function dividendReceived(User $user, float $amount, string $ticker): void
    {
        $this->notificationService->createNotification(
            $user,
            '💰 Dividendo Ricevuto',
            sprintf(
                'Hai ricevuto %.2f CFU di dividendi da $%s!',
                $amount,
                $ticker
            )
        );
    }

    /**
     * Send notification when a transaction is completed successfully.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function transactionCompleted(Transaction $transaction): void
    {
        $type = $transaction->type === 'buy' ? 'acquisto' : 'vendita';
        
        $this->notificationService->createNotification(
            $transaction->user,
            '✅ Transazione Completata',
            sprintf(
                'La tua %s di %d azioni di $%s è stata eseguita con successo!',
                $type,
                $transaction->quantity,
                $transaction->meme->ticker
            )
        );
    }

    /**
     * Send notification when a transaction fails.
     *
     * @param User $user
     * @param string $reason
     * @param string|null $ticker
     * @return void
     */
    public function transactionFailed(User $user, string $reason, ?string $ticker = null): void
    {
        $message = $ticker 
            ? sprintf('Transazione per $%s fallita: %s', $ticker, $reason)
            : sprintf('Transazione fallita: %s', $reason);

        $this->notificationService->createNotification(
            $user,
            '❌ Errore Transazione',
            $message
        );
    }   
}
