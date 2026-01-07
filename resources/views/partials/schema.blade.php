@php
    $personSchema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "@id" => url('/') . "/#person",
        "name" => "صالح الشهري",
        "alternateName" => "Saleh Alshehry",
        "givenName" => "صالح",
        "familyName" => "الشهري",
        "jobTitle" => "خبير ريادة الأعمال ومستشار في منشآت",
        "description" => "صالح الشهري - CEO وخبير ريادة الأعمال، حاصل على ماجستير في ريادة الأعمال. مستشار معتمد في هيئة المنشآت الصغيرة والمتوسطة (منشآت). متخصص في تأسيس المشاريع الناشئة، التدريب والتطوير، وتحويل الأفكار الإبداعية إلى مشاريع ناجحة.",
        "url" => url('/'),
        "image" => asset('images/saleh-alshehry-og.jpg'),
        "sameAs" => [
            "https://x.com/alshehrysaleh",
            "https://www.linkedin.com/in/alshehrysaleh",
            "https://twitter.com/alshehrysaleh"
        ],
        "knowsAbout" => [
            "ريادة الأعمال",
            "تأسيس المشاريع",
            "الشركات الناشئة",
            "التدريب والتطوير",
            "استشارات الأعمال",
            "الابتكار",
            "Entrepreneurship",
            "Startups",
            "Business Consulting"
        ],
        "alumniOf" => [
            "@type" => "EducationalOrganization",
            "name" => "ماجستير ريادة الأعمال"
        ],
        "worksFor" => [
            "@type" => "Organization",
            "name" => "منشآت - هيئة المنشآت الصغيرة والمتوسطة",
            "url" => "https://www.monshaat.gov.sa"
        ],
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "جدة",
            "addressCountry" => "SA"
        ]
    ];

    $websiteSchema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "@id" => url('/') . "/#website",
        "name" => "صالح الشهري | Saleh Alshehry",
        "alternateName" => "مدونة صالح الشهري",
        "description" => "مدونة صالح الشهري - خبير ريادة الأعمال ومستشار في منشآت. مقالات متخصصة في تأسيس المشاريع، الابتكار، والتدريب والتطوير.",
        "url" => url('/'),
        "inLanguage" => "ar",
        "publisher" => [
            "@id" => url('/') . "/#person"
        ],
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => [
                "@type" => "EntryPoint",
                "urlTemplate" => route('search') . "?q={search_term_string}"
            ],
            "query-input" => "required name=search_term_string"
        ]
    ];
@endphp

{{-- Global Person Schema --}}
<script type="application/ld+json">
    {!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- WebSite Schema --}}
<script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
