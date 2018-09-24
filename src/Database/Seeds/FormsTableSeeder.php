<?php

namespace RefinedDigital\Comments\Database\Seeds;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // first, install the table
        $id = \DB::table('forms')->insertGetId([
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
            'active'        => 1,
            'position'      => 0,
            'form_action'   => 2,
            'recaptcha'     => 1,
            'name'          => 'Comments',
            'subject'       => 'New Comment',
            'email_to'      => 'matthias@refineddigital.co.nz',
            'message'       => '<p>You have a new comment</p><p>[[fields]]</p>',
            'confirmation'  => '<p>Thanks for posting a comment.</p><p>Your comment has been sent to moderation</p>',
            'callback'      => '\RefinedDigital\Comments\Something'
        ]);

        // now insert the fields
        $fields = [
            ['form_id' => $id, 'form_field_type_id' => 1, 'active' => 1, 'show_label' => 1, 'position' => 0, 'name' => 'Name',    'required' => 1],
            ['form_id' => $id, 'form_field_type_id' => 8, 'active' => 1, 'show_label' => 1, 'position' => 1, 'name' => 'Email',   'required' => 1],
            ['form_id' => $id, 'form_field_type_id' => 2, 'active' => 1, 'show_label' => 1, 'position' => 2, 'name' => 'Comment', 'required' => 1],
            ['form_id' => $id, 'form_field_type_id' => 6, 'active' => 1, 'show_label' => 1, 'position' => 3, 'name' => 'Remember me for the next time I comment', 'required' => 0],
        ];

        foreach($fields as $field) {
            $field['created_at'] = Carbon::now();
            $field['updated_at'] = Carbon::now();
            \DB::table('form_fields')->insert($field);
        }
    }
}
