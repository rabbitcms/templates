<?php

namespace RabbitCMS\Templates\Entities;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use RabbitCMS\Templates\Contracts\MailRecipient;
use SuperClosure\Serializer;

/**
 * Class Send
 *
 * @property-read int $id
 * @property-read MailRecipient $recipient
 * @property-read Template $template
 * @property string $subject
 * @property string $html
 * @property string $plain
 * @property mixed $callback
 * @property bool $sent
 *
 */
class Send extends Eloquent
{
    protected $table = 'mail_send';

    protected $fillable = [
        'subject',
        'html',
        'plain',
        'callback',
        'sent'
    ];

    /**
     * Get recipient relation.
     * @return MorphTo
     */
    public function recipient()
    {
        return $this->morphTo('recipient');
    }

    /**
     * Get template relation.
     * @return BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function setCallbackAttribute($callback)
    {
        if ($callback instanceof Closure) {
            $callback = (new Serializer)->serialize($callback);
        }

        $this->attributes['callback'] = $callback;
    }

    public function getCallbackAttribute()
    {
        $callback = $this->attributes['callback'];
        if (Str::contains($callback, 'SerializableClosure')) {
            $callback = (new Serializer)->unserialize($callback);
        }

        return $callback;
    }
}