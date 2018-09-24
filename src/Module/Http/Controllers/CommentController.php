<?php

namespace RefinedDigital\Comments\Module\Http\Controllers;

use RefinedDigital\CMS\Modules\Core\Http\Controllers\CoreController;
use RefinedDigital\Comments\Module\Http\Requests\CommentRequest;
use RefinedDigital\Comments\Module\Http\Repositories\CommentRepository;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;

class CommentController extends CoreController
{
    protected $model = 'RefinedDigital\Comments\Module\Models\Comment';
    protected $prefix = 'Comments::';
    protected $route = 'comments';
    protected $heading = 'Comments';
    protected $button = 'a Link';

    protected $CommentRepository;

    public function __construct(CoreRepository $coreRepository)
    {
        $this->CommentRepository = new CoreRepository();
        $this->CommentRepository->setModel($this->model);

        parent::__construct($coreRepository);
    }

    public function setup()
    {

        $table = new \stdClass();
        $table->fields = [
            (object) [ 'name' => 'Name', 'field' => 'name',],
            (object) [ 'name' => 'Comment', 'field' => 'comment', 'type' => 'clipped' ],
            (object) [ 'name' => 'Type', 'field' => 'commentable_type', 'type' => 'className'],
            (object) [ 'name' => 'Post', 'field' => 'commentable_id', 'type' => 'comment'],
            (object) [ 'name' => 'Approved', 'field' => 'approved', 'type'=> 'select', 'options' => [1 => 'Yes', 0 => 'No'], 'classes' => ['data-table__cell--active']],
            (object) [ 'name' => 'Submitted On', 'field' => 'created_at', 'type'=> 'comment-datetime'],
        ];
        $table->routes = (object) [
            'edit'      => 'refined.comments.edit',
            'destroy'   => 'refined.comments.destroy'
        ];
        $table->sortable = true;

        $this->table = $table;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($item)
    {
        // get the instance
        $data = $this->model::findOrFail($item);

        return parent::edit($data);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        return parent::storeRecord($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        return parent::updateRecord($request, $id);
    }

}
