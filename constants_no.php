<?php
return [
    'db' => [
        'servername' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'taxdb',
    ],
    'questions' => [
        'permanent_home' => 'Har du en fast bolig i land A, land B, begge, eller ingen?',
        'vital_interests' => 'Har du vitale interesser i land A, land B, begge, eller ingen?',
        'habitual_abode' => 'Har du en vanlig bolig i land A, land B, begge, eller ingen?',
        'citizenship' => 'Er du statsborger i land A, land B, begge, eller ingen?',
    ],
    'display' => [
        'case_summary_title' => 'Sammendrag av saken',
        'double_tax_treaty_title' => 'Evaluering av dobbeltbeskatningsavtale',
        'case_summary_for' => 'Sammendrag av saken for',
        'case_id' => 'Saks-ID:',
        'initial_tax_liability' => 'Innledende skatteansvar:',
        'tax_question' => 'Skattespørsmål:',
        'created_at' => 'Opprettet:',
        'conclusion' => 'Konklusjon',
        'tax_residency' => 'Skattebosted:',
        'double_tax_residency' => 'Dobbelt skattebosted:',
        'submitted_answers' => 'Innsendte svar',
        'no_answers_submitted' => 'Ingen svar sendt inn ennå.',
        'double_tax_treaty_answers' => 'Svar på dobbeltbeskatningsavtale',
        'pending' => 'Avventer',
        'determine_tax_residency' => 'Bestem skattebosted for',
        'access_denied' => 'Tilgang nektet. Du må være bosatt i Skatt B for å få tilgang til denne siden.',
        'next' => 'Neste',
        'country_a' => 'Land A',
        'country_b' => 'Land B',
        'both' => 'Begge land',
        'neither' => 'Ingen av dem',
        'tax_liability_clarification' => 'Avklaring av skatteansvar',
        'case_name' => 'Saksnavn:',
        'initial_tax_status' => 'Innledende skatteansvar:',
        'create_case' => 'Opprett sak',
        'previous_cases' => 'Tidligere saker',
        'view_summary' => 'Vis sammendrag',
        'initial_clarification' => 'Innledende avklaring',
        'double_tax_treaty' => 'Dobbeltbeskatningsavtale',
        'please_answer_questions' => 'Vennligst svar på følgende spørsmål:',
    ],
    'translated_questions' => [
        'permanent_home' => 'Fast bolig',
        'vital_interests' => 'Vitale interesser',
        'habitual_abode' => 'Vanlig opphold',
        'citizenship' => 'Statsborgerskap',
    ],
    'translated_answers' => [
        'yes' => 'Ja',
        'no' => 'Nei',
        'Country A' => 'Land A',
        'Country B' => 'Land B',
        'Both Countries' => 'Begge land',
        'Neither' => 'Ingen av dem',
    ],
    'dynamic_form_questions' => [
        'stayPermanent' => 'Har personen tatt fast opphold i utlandet?',
        'taxResidentA10' => 'Har personen vært skattepliktig i Norge i mer enn 10 år før flytting til utlandet?',
        'stayA61' => 'Har personen oppholdt seg i Norge i mer enn 61 dager i inntektsåret?',
        'homeAccessA' => 'Har personen eller deres nærmeste familie hatt tilgang til en bolig i Norge?',
        'threeYearRule' => 'For hvert av de tre årene etter flytting til utlandet, har personen oppholdt seg i Norge i mer enn 61 dager ELLER hatt tilgang til en bolig i Norge i NOEN av disse årene?',
        'residentTaxB' => 'Er personen skattepliktig i det andre landet?',
    ],
    'dynamic_form_answers' => [
        'yes' => 'Ja',
        'no' => 'Nei',
    ],
];