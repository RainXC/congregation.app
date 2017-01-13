<?php namespace App\SpeakersApp\Discourse\Model;

use Eloquent;
use App\Admin;

/**
 * Class Status
 *
 * @property \App\Admin                $author
 */
class Status extends \App\Status{

    /**
     * @var string
     */
    protected $table = 'discourses_assignments_statuses';
}
