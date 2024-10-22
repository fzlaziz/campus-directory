<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accreditation extends Model
{
    /** @use HasFactory<\Database\Factories\AccreditationFactory> */
    use HasFactory;

    public function campuses(): HasMany
    {
        return $this->hasMany(Campus::class);
    }

    public function study_programs(): HasMany
    {
        return $this->hasMany(StudyProgram::class);
    }
}
