<?php


class RpcController extends BaseController
{
    public function __construct(GContacts\Google\SharedContactsInterface $contacts)
    {
        $this->contacts = $contacts;
    }

    public function addRow($key, $index)
    {
        // map some stuff:
        $keys = [
            'organization' =>
                [
                    'tpl' => 'organization',
                    'arr' => 'organization',
                ],
            'phoneNumber'  =>
                [
                    'tpl' => 'phone',
                    'arr' => 'phoneNumber'
                ]
            ,
            'structuredPostalAddress'  =>
                [
                    'tpl' => 'address',
                    'arr' => 'structuredPostalAddress'
                ]
        ];
        $tpl = isset($keys[$key]) ? $keys[$key]['tpl'] : $key;
        $indexName = isset($keys[$key]) ? $keys[$key]['arr'] : $key;

        $index = $index - 1;


        $contact = [
            $indexName => [
                $index => []
            ]
        ];

        $view = View::make('edit/' . $tpl)->with('contact', $contact);
        $html = $view->render();
        return $html;
    }

} 