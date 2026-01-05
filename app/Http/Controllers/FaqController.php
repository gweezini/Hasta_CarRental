<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'How to book a car for rental?',
                'answer' => "Kindly please contact us using whatsapp\n1. Fill in the booking form\n2. Receive booking confirmation\n3. Pay deposit"
            ],
            [
                'question' => 'When will my deposit be returned?',
                'answer' => 'Deposits will be returned after 7 working days if there are no issues after inspection has been made.'
            ],
            [
                'question' => 'Can I rent if im still having a P license.',
                'answer' => 'Yes. You can still rent with us.'
            ],
            [
                'question' => 'Can i pickup the car outside of UTM',
                'answer' => 'Yes, delivery charges will apply to any pickup location besides Hasta Travel Office in UTM.'
            ],
            [
                'question' => 'Can i pickup the car at night?',
                'answer' => 'Yes, extra charges will apply picking up outside office hours.'
            ]
        ];

        return view('faq', compact('faqs'));
    }
}
