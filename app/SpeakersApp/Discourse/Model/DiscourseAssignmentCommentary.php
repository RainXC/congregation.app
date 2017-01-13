<?php

namespace App\SpeakersApp\Discourse\Model;

use Illuminate\Database\Eloquent\Model;

class DiscourseAssignmentCommentary extends Model
{
    protected $table = 'discourses_assignments_commentaries';

    public function getDate()
    {
        return $this->created_at;
    }

    public function getText()
    {
        return $this->text;
    }
}