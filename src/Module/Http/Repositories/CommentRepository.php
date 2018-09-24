<?php

namespace RefinedDigital\Comments\Module\Http\Repositories;


use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\Comments\Module\Models\Comment;
use RefinedDigital\FormBuilder\Module\Http\Repositories\FormBuilderRepository;
use RefinedDigital\FormBuilder\Module\Http\Repositories\FormsRepository;

class CommentRepository
{
    protected $orderDirection = 'desc';
    protected $orderColumn = 'created_at';

    public function getForm()
    {
        $formRepo = new FormsRepository(new FormBuilderRepository());
        return $formRepo->form('Comments')->setButtonText('Post Comment')->render();
    }

    public function getComments($model)
    {

        if (isset($model->id)) {
            return $this->getAllCommentsByModel($model->id, get_class($model));
        }

        return collect([]);
    }

    public function getCount($model)
    {

        if (isset($model->id)) {
            return Comment::whereCommentableId($model->id)
                        ->whereCommentableType(get_class($model))
                        ->whereApproved(1)
                        ->count();
        }

        return 0;
    }

    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
        return $this;
    }

    public function setOrderColumn($orderColumn)
    {
        $this->orderColumn = $orderColumn;
        return $this;
    }

    private function getAllCommentsByModel($typeId, $typeModel, $parent = 0)
    {
        $comments = Comment::whereCommentableId($typeId)
                        ->whereCommentableType($typeModel)
                        ->whereParentId($parent)
                        ->whereApproved(1)
                        ->orderBy($this->orderColumn, $this->orderDirection)
                        ->get();

        // check for child comments
        if ($comments->count()) {
            foreach ($comments as $comment) {
                $comment->children = $this->getAllCommentsByModel($typeId, $typeModel, $comment->id);
            }
        }

        return $comments;
    }
}
