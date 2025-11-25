<?php

namespace App\Observers;

use App\Models\Buget;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $buget = Buget::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->first();
        if ($buget && $transaction->amount > 0 && $transaction->category->type === 'egreso') {
            $buget->spend_amount += $transaction->amount;
            $buget->save();
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Obtener los valores originales antes del update
        $originalAmount = $transaction->getOriginal('amount');
        $originalCategoryId = $transaction->getOriginal('category_id');

        // Si la categoria o el monto cambian, actualizar ambos presupuestos
        if ($transaction->category_id !== $originalCategoryId) {
            // Revertir en el presupuesto anterior
            $oldBuget = Buget::where('user_id', $transaction->user_id)
                ->where('category_id', $originalCategoryId)
                ->first();
            if ($oldBuget && $transaction->category->type === 'egreso') {
                $oldBuget->spend_amount -= $originalAmount;
                $oldBuget->save();
            }
            // Sumar en el nuevo presupuesto
            $newBuget = Buget::where('user_id', $transaction->user_id)
                ->where('category_id', $transaction->category_id)
                ->first();
            if ($newBuget && $transaction->amount > 0 && $transaction->category->type === 'egreso') {
                $newBuget->spend_amount += $transaction->amount;
                $newBuget->save();
            }
        } else {
            // Si solo cambia el monto
            $buget = Buget::where('user_id', $transaction->user_id)
                ->where('category_id', $transaction->category_id)
                ->first();
            if ($buget && $transaction->category->type === 'egreso') {
                $diferencia = $transaction->amount - $originalAmount;
                $buget->spend_amount += $diferencia;
                $buget->save();
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $buget = Buget::where('user_id', $transaction->user_id)
            ->where('category_id', $transaction->category_id)
            ->first();
        if ($buget && $transaction->amount > 0 && $transaction->category->type === 'egreso') {
            $buget->spend_amount -= $transaction->amount;
            $buget->save();
        }
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
