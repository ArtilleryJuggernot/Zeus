<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Habitude
 *
 * Ce modèle représente une habitude utilisateur, qui permet de générer automatiquement des tâches récurrentes (routines).
 *
 * Champs principaux :
 * - id : identifiant unique de l'habitude
 * - user_id : utilisateur propriétaire
 * - task_id : identifiant de la tâche associée (pour le rappel ou le suivi)
 * - is_enable : 1 = active, 0 = désactivée (pause)
 * - name : nom de l'habitude
 *
 * Utilisation :
 * - Permet de stocker les routines de l'utilisateur (ex : "Boire de l'eau", "Sport du matin")
 * - Génère des tâches automatiques selon la configuration (jours, horaires)
 * - Peut être activée/désactivée sans suppression
 */
class Habitude extends Model
{
    use HasFactory;
    protected $table = "habitude";
    public $timestamps = false;

    // Relations potentielles (exemple)
    // public function user() { return $this->belongsTo(User::class, 'user_id'); }
    // public function task() { return $this->belongsTo(Task::class, 'task_id'); }
}
