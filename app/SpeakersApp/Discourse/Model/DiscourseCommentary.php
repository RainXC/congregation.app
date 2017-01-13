<?php

namespace App\SpeakersApp\Discourse\Model;

use App\SpeakersApp\Speaker\Model\Speaker;
use App\SpeakersApp\Speech\Model\Speech;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class DiscourseCommentary extends Model
{
    const STATUS_ACTIVE = 1;

    /**
     * @var bool
     */
    public static $snakeAttributes = false;

    /**
     * @var string
     */
    protected $table = 'discourses_commentaries';

    /**
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * @var array
     */
    protected $appends = ['comment'];

    public function getCommentAttribute()
    {
        if ( $this->isJson( $this->text ) ) {
            $data = json_decode($this->text);
            $speaker = Speaker::findOrFail($data->speaker);
            $speech  = Speech::findOrFail($data->speech);
            return Lang::get('commentaries.'.$data->langKey, [
                'speaker' => $speaker->getName()?$speaker->getName():'',
                'speech'  => $speech->getName()?$speech->getName():'',
            ]);
        }
        $temp = json_encode(['test'=>'test']);
        json_decode($temp);

        return str_replace("\r\n", "<br>", $this->text);
    }

    public function getText()
    {
        if ( $this->isJson( $this->text ) ) {
            $data = json_decode($this->text);
            return Lang::get('commentaries.'.$data->langKey, [
                'speaker' => $data->speaker,
                'speech'  => $data->speech,
            ]);
        }

        return str_replace("\r\n", "<br>", $this->text);
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
