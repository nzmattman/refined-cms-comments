<?php

namespace RefinedDigital\Comments\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use Spatie\EloquentSortable\Sortable;

class Comment extends CoreModel implements Sortable
{
    use SoftDeletes;

    protected $fillable = [
        'active', 'approved', 'position', 'commentable_id', 'commentable_type', 'name', 'comment', 'email', 'parent_id',
    ];

    /**
     * The fields to be displayed for creating / editing
     *
     * @var array
     */
    public $formFields = [
        [
            'name' => 'Content',
            'fields' => [
                [
                    [ 'label' => 'Approved', 'name' => 'approved', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                ],
                [
                    [ 'label' => 'Name', 'name' => 'name', 'type' => 'readonly'],
                    [ 'label' => 'Email', 'name' => 'email', 'type' => 'readonly'],
                ],
                [
                    [ 'label' => 'Type', 'name' => 'commentable_type', 'type' => 'className'],
                    [ 'label' => 'Post', 'name' => 'commentable_id', 'type' => 'comment'],
                ],
                [
                    [ 'label' => 'Comment', 'name' => 'comment', 'required' => true, 'type' => 'textarea'],
                ]
            ]
        ]
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
}
