<?php

namespace RefinedDigital\Comments\Helpers;

use RefinedDigital\FormBuilder\Module\Contracts\FormBuilderCallbackInterface;
use RefinedDigital\FormBuilder\Module\Http\Repositories\FormBuilderRepository;
use RefinedDigital\FormBuilder\Module\Http\Repositories\FormsRepository;

class Save implements FormBuilderCallbackInterface {

    // todo: make the type, id and parent_id dynamic
    public function run( $request, $form ) {
        $formBuilderRepository = new FormBuilderRepository();
        $formBuilderRepository->setModel('RefinedDigital\Comments\Module\Models\Comment');
        $repo = new FormsRepository($formBuilderRepository);
        $fields = $repo->formatFieldsByName($request, $form);
        $type = $request->has('modelType') ? $request->get('modelType') : 'RefinedDigital\Blog\Module\Models\Blog';
        $id = $request->has('modelId') ? $request->get('modelId') : '1';

        $insertData = [
            'commentable_id' => $id,
            'commentable_type' => $type,
            'parent_id' => $request->has('parent') ? $request->get('parent') : 0,
            'name' => (isset($fields['Name']) && $fields['Name']) ? $fields['Name'] : null,
            'comment' => (isset($fields['Comment']) && $fields['Comment']) ? $fields['Comment'] : null,
            'email' => (isset($fields['Email']) && $fields['Email']) ? $fields['Email'] : null,
        ];

        $formBuilderRepository->store($insertData);

        // if they want to be remembered, store this in a cookie
        if (isset($fields['Remember me for the next time I comment']) && $fields['Remember me for the next time I comment']) {
            $contact = [];
            $contact['name'] = (isset($fields['Name']) && $fields['Name']) ? $fields['Name'] : null;
            $contact['email'] = (isset($fields['Email']) && $fields['Email']) ? $fields['Email'] : null;
            setcookie('comments', json_encode($contact), time() + (86400 * 30), "/"); // 86400 = 1 day
        }

        // now send the new email message
        $formBuilderRepository->compileAndSend($request, $form);

        return redirect()->back()->with('complete', 1)->with('form', $form);
    }

}
