<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Hasta Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Syncopate:wght@400;700&display=swap");

        :root {
            --primary-color: #ec5a29;
            --text-dark: #15191d;
            --text-light: #737373;
            --white: #ffffff;
            --bg-gray: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", sans-serif;
            background-color: #2d3748;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .header {
            position: relative;
            background-image: linear-gradient(
                rgba(0, 0, 0, 0.6),
                rgba(0, 0, 0, 0.4)
            ),
            url("{{ asset('images/hastabackground.png') }}");
            background-size: cover;
            background-position: center;
            padding: 4rem 2rem;
            text-align: center;
            color: var(--white);
        }

        .header h1 {
            font-family: "Syncopate", sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .container {
            max-width: 800px;
            margin: -3rem auto 5rem;
            padding: 0 1.5rem;
            position: relative;
            z-index: 10;
        }

        .faq_card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .faq_item {
            border-bottom: 1px solid #eee;
        }
        .faq_item:last-child {
            border-bottom: none;
        }

        .faq_question {
            width: 100%;
            text-align: left;
            padding: 1.5rem;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
            transition: 0.3s;
        }

        .faq_question:hover {
            color: var(--primary-color);
        }

        .faq_answer {
            padding: 0 1.5rem 1.5rem;
            color: var(--text-light);
            font-size: 0.95rem;
            white-space: pre-line;
        }

        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: var(--white);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 20;
        }

        .back-btn:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>

    <div class="header">
        <a href="{{ route('dashboard') }}" class="back-btn">
            <i class="ri-arrow-left-line"></i> Back to Dashboard
        </a>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about our car rental services.</p>
    </div>

    <div class="container">
        <div class="faq_card">
            @foreach($faqs as $index => $faq)
                <div class="faq_item" x-data="{ open: false }">
                    <button @click="open = !open" class="faq_question">
                        <span>{{ $faq['question'] }}</span>
                        <i :class="open ? 'ri-subtract-line' : 'ri-add-line'" style="font-size: 1.2rem; color: var(--primary-color);"></i>
                    </button>
                    <div x-show="open" x-collapse class="faq_answer">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
