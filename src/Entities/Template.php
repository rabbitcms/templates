<?php

namespace RabbitCMS\Templates\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Template
 *
 * @property-read int $id
 * @property      string $name
 * @property      string $extends
 * @property      string $locale
 * @property      string $subject
 * @property      string $plain
 * @property      string $template
 * @property      bool $enabled
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Template extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mail_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'locale',
        'subject',
        'caption',
        'plain',
        'enabled',
        'template',
        'extends'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'bool',
    ];

    /**
     * Get mails relation.
     * @return HasMany
     */
    public function sends()
    {
        return $this->hasMany(Send::class, 'template_id');
    }
}