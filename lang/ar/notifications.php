<?php

return [
    'book_request' => [
        'greeting' => 'مرحباً :name،',
        'note' => 'ملاحظة من أمين المكتبة: :note',
        'action' => 'عرض طلباتي',
        'thanks' => 'شكراً لاستخدامك مكتبتنا!',

        'status' => [
            'new' => [
                'subject' => 'تم استلام الطلب: :title',
                'message' => 'لقد استلمنا طلبك لـ ":book_display". سنقوم بمراجعته قريباً.',
                'db_message' => 'تم استلام طلبك لـ ":title".',
                'db_message_with_author' => 'تم استلام طلبك لـ ":title" بقلم :author.',
            ],
            'read' => [
                'subject' => 'الطلب قيد المراجعة: :title',
                'message' => 'طلبك لـ ":book_display" قيد المراجعة حالياً من قبل فريقنا.',
                'db_message' => 'طلبك لـ ":title" قيد المراجعة.',
                'db_message_with_author' => 'طلبك لـ ":title" بقلم :author قيد المراجعة.',
            ],
            'processed' => [
                'subject' => 'تمت معالجة الطلب: :title',
                'message' => 'أخبار رائعة! تمت معالجة طلبك لـ ":book_display" وتم توفيره.',
                'db_message' => 'تمت معالجة طلبك لـ ":title".',
                'db_message_with_author' => 'تمت معالجة طلبك لـ ":title" بقلم :author.',
            ],
            'rejected' => [
                'subject' => 'تحديث بخصوص طلبك: :title',
                'message' => 'عذراً، لا يمكننا تلبية طلبك لـ ":book_display" في الوقت الحالي.',
                'db_message' => 'تم رفض طلبك لـ ":title".',
                'db_message_with_author' => 'تم رفض طلبك لـ ":title" بقلم :author.',
            ],
        ],
    ],

    'overdue' => [
        'subject' => 'إشعار تأخير: :title',
        'greeting' => 'مرحباً :name،',
        'message' => 'تظهر سجلاتنا أن الكتاب المستعار ":title" متأخر عن موعد الإرجاع بمقدار :days أيام.',
        'action' => 'عرض استعاراني',
        'warning' => 'يرجى إرجاع هذا الكتاب في أقرب وقت ممكن لتجنب أي غرامات تأخير إضافية.',
        'db_message' => 'الكتاب ":title" متأخر بمقدار :days أيام.',
    ],
];