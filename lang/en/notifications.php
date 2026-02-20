<?php

return [
    'book_request' => [
        'greeting' => 'Hi :name,',
        'note' => 'Note from the librarian: :note',
        'action' => 'View My Requests',
        'thanks' => 'Thank you for using our library!',

        'status' => [
            'new' => [
                'subject' => 'Request Received: :title',
                'message' => 'We have received your request for ":book_display". We will review it shortly.',
                'db_message' => 'Your request for ":title" was received.',
                'db_message_with_author' => 'Your request for ":title" by :author was received.',
            ],
            'read' => [
                'subject' => 'Request Under Review: :title',
                'message' => 'Your request for ":book_display" is currently under review by our team.',
                'db_message' => 'Your request for ":title" is under review.',
                'db_message_with_author' => 'Your request for ":title" by :author is under review.',
            ],
            'processed' => [
                'subject' => 'Request Processed: :title',
                'message' => 'Great news! Your request for ":book_display" has been processed and acquired.',
                'db_message' => 'Your request for ":title" has been processed.',
                'db_message_with_author' => 'Your request for ":title" by :author has been processed.',
            ],
            'rejected' => [
                'subject' => 'Update on your request: :title',
                'message' => 'Unfortunately, we are unable to fulfill your request for ":book_display" at this time.',
                'db_message' => 'Your request for ":title" was declined.',
                'db_message_with_author' => 'Your request for ":title" by :author was declined.',
            ],
        ],
    ],

    'overdue' => [
            'subject' => 'Overdue Notice: :title',
            'greeting' => 'Hi :name,',
            'message' => 'Our records show that your borrowed book, ":title", is now :days days overdue.',
            'action' => 'View My Loans to Return',
            'warning' => 'Please return this book as soon as possible to avoid any further late fees.',
            'db_message' => 'Your book ":title" is :days days overdue.',
    ],

    'waiting_list' => [
        'greeting' => 'Hi :name,',
        'available_subject' => 'Book Available: :title',
        'available_db_message' => 'Good news! The book ":title" is now available.',
        'thanks' => 'Thank you for using our library!',
    ],
];